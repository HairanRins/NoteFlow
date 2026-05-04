<script setup>
import { computed, onBeforeUnmount, onMounted, ref, watch } from 'vue';
import { Head } from '@inertiajs/vue3';
import axios from 'axios';
import { clearQueuedActions, getQueuedActions, loadSnapshot, queueAction, saveSnapshot } from '@/lib/indexedDb';
import { renderMarkdown } from '@/lib/markdown';

const notes = ref([]);
const tags = ref([]);
const searchQuery = ref('');
const selectedTag = ref('');
const activeNoteId = ref(null);
const editorTitle = ref('');
const editorContent = ref('');
const quickCaptureOpen = ref(false);
const quickCaptureValue = ref('');
const syncStatus = ref('loading');
const lastSyncedAt = ref(null);
const isHydrated = ref(false);

let saveTimer = null;
let syncTimer = null;

const filteredNotes = computed(() => {
    const query = searchQuery.value.trim().toLowerCase();
    const tag = selectedTag.value;

    return notes.value
        .filter((note) => !note.is_deleted)
        .filter((note) => {
            if (tag && !note.tags.some((item) => item.name === tag)) {
                return false;
            }

            if (!query) {
                return true;
            }

            const haystack = `${note.title} ${note.content} ${note.tags.map((item) => item.name).join(' ')}`.toLowerCase();
            return haystack.includes(query);
        })
        .sort((left, right) => new Date(right.updated_at) - new Date(left.updated_at));
});

const activeNote = computed(() => filteredNotes.value.find((note) => note.id === activeNoteId.value)
    ?? notes.value.find((note) => note.id === activeNoteId.value)
    ?? null);

const recentNotes = computed(() => filteredNotes.value.slice(0, 5));
const linkedNotes = computed(() => {
    if (!activeNote.value) {
        return [];
    }

    const outgoing = activeNote.value.outgoing_links.map((link) => ({
        id: `out-${link.target_note_id}`,
        note_id: link.target_note_id,
        title: link.target_note_title,
        direction: 'Outgoing',
    }));

    const incoming = activeNote.value.incoming_links.map((link) => ({
        id: `in-${link.source_note_id}`,
        note_id: link.source_note_id,
        title: link.source_note_title,
        direction: 'Backlink',
    }));

    return [...outgoing, ...incoming];
});
const currentTags = computed(() => extractTags(editorContent.value));
const notesCount = computed(() => notes.value.filter((note) => !note.is_deleted).length);
const previewHtml = computed(() => renderMarkdown(editorContent.value));

watch(activeNote, (note) => {
    if (!note) {
        editorTitle.value = '';
        editorContent.value = '';
        return;
    }

    editorTitle.value = note.title ?? '';
    editorContent.value = note.content ?? '';
}, { immediate: true });

watch([editorTitle, editorContent], () => {
    if (!isHydrated.value || !activeNote.value) {
        return;
    }

    if (editorTitle.value === activeNote.value.title && editorContent.value === activeNote.value.content) {
        return;
    }

    if (saveTimer) {
        window.clearTimeout(saveTimer);
    }

    saveTimer = window.setTimeout(() => {
        const timestamp = new Date().toISOString();
        patchLocalNote(activeNote.value.id, {
            title: editorTitle.value.trim() || titleFromContent(editorContent.value),
            content: editorContent.value,
            updated_at: timestamp,
            tags: extractTags(editorContent.value).map((name) => ({ id: `local-${name}`, name })),
        });
        enqueueSync('update', {
            id: activeNote.value.id,
            title: editorTitle.value.trim() || titleFromContent(editorContent.value),
            content: editorContent.value,
        });
    }, 500);
});

onMounted(async () => {
    window.addEventListener('keydown', handleKeyboardShortcuts);
    window.addEventListener('online', flushSyncQueue);

    const snapshot = await loadSnapshot('workspace');
    if (snapshot) {
        notes.value = snapshot.notes ?? [];
        tags.value = snapshot.tags ?? [];
        lastSyncedAt.value = snapshot.last_synced_at ?? null;
        if (notes.value.length && !activeNoteId.value) {
            activeNoteId.value = notes.value[0].id;
        }
    }

    await refreshWorkspace();
    isHydrated.value = true;
});

onBeforeUnmount(() => {
    window.removeEventListener('keydown', handleKeyboardShortcuts);
    window.removeEventListener('online', flushSyncQueue);
});

async function refreshWorkspace() {
    syncStatus.value = 'syncing';

    try {
        const { data } = await axios.get('/api/workspace');
        notes.value = (data.notes ?? []).map(normalizeNote);
        tags.value = data.tags ?? [];
        if (!activeNoteId.value || !notes.value.some((note) => note.id === activeNoteId.value && !note.is_deleted)) {
            activeNoteId.value = notes.value[0]?.id ?? null;
        }
        lastSyncedAt.value = data.meta?.generated_at ?? new Date().toISOString();
        syncStatus.value = 'ready';
        await persistWorkspace();
        await flushSyncQueue();
    } catch (error) {
        syncStatus.value = navigator.onLine ? 'error' : 'offline';
    }
}

function handleKeyboardShortcuts(event) {
    if ((event.metaKey || event.ctrlKey) && event.key.toLowerCase() === 'k') {
        event.preventDefault();
        quickCaptureOpen.value = true;
        return;
    }

    if (event.key === 'Escape') {
        quickCaptureOpen.value = false;
    }
}

async function createBlankNote() {
    const note = buildLocalNote({
        title: 'Untitled',
        content: '',
    });

    notes.value = [note, ...notes.value];
    activeNoteId.value = note.id;
    await enqueueSync('create', {
        id: note.id,
        title: note.title,
        content: note.content,
    });
}

async function createQuickCapture() {
    const value = quickCaptureValue.value.trim();

    if (!value) {
        quickCaptureOpen.value = false;
        return;
    }

    const note = buildLocalNote({
        title: titleFromContent(value),
        content: value,
        tags: extractTags(value).map((name) => ({ id: `local-${name}`, name })),
    });

    notes.value = [note, ...notes.value];
    activeNoteId.value = note.id;
    quickCaptureValue.value = '';
    quickCaptureOpen.value = false;
    await enqueueSync('create', {
        id: note.id,
        title: note.title,
        content: note.content,
    });
}

async function deleteCurrentNote() {
    if (!activeNote.value) {
        return;
    }

    patchLocalNote(activeNote.value.id, {
        is_deleted: true,
        updated_at: new Date().toISOString(),
    });

    await enqueueSync('delete', { id: activeNote.value.id });

    activeNoteId.value = filteredNotes.value[0]?.id ?? null;
}

async function enqueueSync(type, payload) {
    syncStatus.value = navigator.onLine ? 'syncing' : 'offline';

    await queueAction({
        id: crypto.randomUUID(),
        type,
        payload,
        created_at: new Date().toISOString(),
    });

    await persistWorkspace();
    scheduleSync();
}

function scheduleSync() {
    if (syncTimer) {
        window.clearTimeout(syncTimer);
    }

    syncTimer = window.setTimeout(() => {
        void flushSyncQueue();
    }, 700);
}

async function flushSyncQueue() {
    if (!navigator.onLine) {
        syncStatus.value = 'offline';
        return;
    }

    const actions = await getQueuedActions();
    if (!actions.length) {
        syncStatus.value = 'ready';
        return;
    }

    syncStatus.value = 'syncing';

    try {
        await axios.post('/api/sync', {
            actions: actions.map(({ type, payload }) => ({ type, payload })),
        });

        await clearQueuedActions(actions.map((action) => action.id));
        const { data } = await axios.get('/api/workspace');
        notes.value = (data.notes ?? []).map(normalizeNote);
        tags.value = data.tags ?? [];
        lastSyncedAt.value = data.meta?.generated_at ?? new Date().toISOString();
        if (!activeNoteId.value || !notes.value.some((note) => note.id === activeNoteId.value && !note.is_deleted)) {
            activeNoteId.value = notes.value[0]?.id ?? null;
        }
        syncStatus.value = 'ready';
        await persistWorkspace();
    } catch (error) {
        syncStatus.value = 'error';
    }
}

async function persistWorkspace() {
    await saveSnapshot('workspace', {
        notes: notes.value,
        tags: tags.value,
        last_synced_at: lastSyncedAt.value,
    });
}

function patchLocalNote(id, changes) {
    notes.value = notes.value.map((note) => {
        if (note.id !== id) {
            return note;
        }

        return normalizeNote({
            ...note,
            ...changes,
        });
    });

    tags.value = aggregateTags(notes.value);
    void persistWorkspace();
}

function buildLocalNote({ title, content, tags: noteTags = [] }) {
    const timestamp = new Date().toISOString();

    return normalizeNote({
        id: crypto.randomUUID(),
        title,
        content,
        is_deleted: false,
        created_at: timestamp,
        updated_at: timestamp,
        tags: noteTags,
        outgoing_links: extractLinks(content).map((link) => ({
            id: `local-out-${link}`,
            target_note_id: `pending-${link}`,
            target_note_title: link,
        })),
        incoming_links: [],
    });
}

function normalizeNote(note) {
    return {
        id: note.id,
        title: note.title ?? 'Untitled',
        content: note.content ?? '',
        is_deleted: Boolean(note.is_deleted),
        created_at: note.created_at ?? new Date().toISOString(),
        updated_at: note.updated_at ?? new Date().toISOString(),
        tags: note.tags ?? [],
        outgoing_links: note.outgoing_links ?? [],
        incoming_links: note.incoming_links ?? [],
    };
}

function aggregateTags(collection) {
    const counts = new Map();

    collection
        .filter((note) => !note.is_deleted)
        .forEach((note) => {
            note.tags.forEach((tag) => {
                counts.set(tag.name, (counts.get(tag.name) ?? 0) + 1);
            });
        });

    return [...counts.entries()]
        .map(([name, count], index) => ({ id: `aggregate-${index}-${name}`, name, count }))
        .sort((left, right) => left.name.localeCompare(right.name));
}

function extractTags(content) {
    const matches = content.match(/(^|\s)#([A-Za-z0-9\-_]+)/g) ?? [];
    return [...new Set(matches.map((match) => match.trim().replace(/^#/, '').replace(/\s#/, '')))]
        .map((tag) => tag.replace(/^#/, '').trim())
        .filter(Boolean);
}

function extractLinks(content) {
    return [...content.matchAll(/\[\[([^\[\]]+)\]\]/g)].map((match) => match[1].trim()).filter(Boolean);
}

function titleFromContent(content) {
    const line = (content ?? '').split('\n').map((item) => item.trim()).find(Boolean);
    return line ? line.replace(/^#+\s*/, '').slice(0, 80) : 'Untitled';
}

function openLinkedNote(noteId, noteTitle) {
    const existing = notes.value.find((note) => note.id === noteId);
    if (existing) {
        activeNoteId.value = existing.id;
        return;
    }

    searchQuery.value = noteTitle;
}

function formatDate(value) {
    if (!value) {
        return 'Never';
    }

    return new Intl.DateTimeFormat('fr-FR', {
        dateStyle: 'medium',
        timeStyle: 'short',
    }).format(new Date(value));
}
</script>

<template>
    <Head title="NoteFlow" />

    <div class="nf-shell">
        <aside class="nf-sidebar">
            <div class="nf-brand">
                <div>
                    <p class="nf-eyebrow">Second cerveau</p>
                    <h1>NoteFlow</h1>
                </div>
                <button class="nf-primary-button" @click="createBlankNote">
                    New note
                </button>
            </div>

            <label class="nf-search">
                <span>Search</span>
                <input
                    v-model="searchQuery"
                    type="search"
                    placeholder="Title, content, #tag"
                >
            </label>

            <section class="nf-section">
                <div class="nf-section-head">
                    <h2>Recents</h2>
                    <span>{{ notesCount }}</span>
                </div>
                <button
                    v-for="note in recentNotes"
                    :key="note.id"
                    class="nf-note-item"
                    :class="{ 'is-active': note.id === activeNoteId }"
                    @click="activeNoteId = note.id"
                >
                    <strong>{{ note.title }}</strong>
                    <span>{{ formatDate(note.updated_at) }}</span>
                </button>
            </section>

            <section class="nf-section">
                <div class="nf-section-head">
                    <h2>Tags</h2>
                    <button class="nf-text-button" @click="selectedTag = ''">
                        Reset
                    </button>
                </div>
                <div class="nf-tag-grid">
                    <button
                        v-for="tag in tags"
                        :key="tag.id"
                        class="nf-chip"
                        :class="{ 'is-active': selectedTag === tag.name }"
                        @click="selectedTag = selectedTag === tag.name ? '' : tag.name"
                    >
                        #{{ tag.name }} <span>{{ tag.count }}</span>
                    </button>
                </div>
            </section>
        </aside>

        <main class="nf-main">
            <header class="nf-topbar">
                <div>
                    <p class="nf-eyebrow">Workspace</p>
                    <h2>Minimaliste, lie, recherche, synchronise.</h2>
                </div>

                <div class="nf-topbar-actions">
                    <button class="nf-ghost-button" @click="quickCaptureOpen = true">
                        Quick capture
                        <kbd>Ctrl K</kbd>
                    </button>
                    <div class="nf-status" :class="`is-${syncStatus}`">
                        <span>{{ syncStatus }}</span>
                        <small>{{ formatDate(lastSyncedAt) }}</small>
                    </div>
                </div>
            </header>

            <div class="nf-workspace">
                <section class="nf-notes-panel">
                    <div class="nf-section-head">
                        <h2>Notes</h2>
                        <span>{{ filteredNotes.length }}</span>
                    </div>
                    <div class="nf-list-panel">
                        <button
                            v-for="note in filteredNotes"
                            :key="note.id"
                            class="nf-list-card"
                            :class="{ 'is-active': note.id === activeNoteId }"
                            @click="activeNoteId = note.id"
                        >
                            <div class="nf-list-card-head">
                                <strong>{{ note.title }}</strong>
                                <span>{{ note.outgoing_links.length + note.incoming_links.length }} links</span>
                            </div>
                            <p>{{ note.content.slice(0, 130) || 'Empty note' }}</p>
                            <div class="nf-inline-tags">
                                <span v-for="tag in note.tags" :key="`${note.id}-${tag.name}`">#{{ tag.name }}</span>
                            </div>
                        </button>
                    </div>
                </section>

                <section class="nf-editor-panel">
                    <template v-if="activeNote">
                        <div class="nf-editor-header">
                            <input
                                v-model="editorTitle"
                                class="nf-title-input"
                                type="text"
                                placeholder="Untitled"
                            >
                            <button class="nf-danger-button" @click="deleteCurrentNote">
                                Delete
                            </button>
                        </div>

                        <div class="nf-meta-row">
                            <span>Auto-save 500ms</span>
                            <span>{{ currentTags.length ? currentTags.map((tag) => `#${tag}`).join(' ') : 'No tags yet' }}</span>
                        </div>

                        <div class="nf-editor-grid">
                            <textarea
                                v-model="editorContent"
                                class="nf-editor"
                                spellcheck="false"
                                placeholder="# Nouvelle idee&#10;&#10;Utilise [[Liens]] et #tags"
                            />

                            <article class="nf-preview" v-html="previewHtml" />
                        </div>
                    </template>

                    <div v-else class="nf-empty-state">
                        <p>Aucune note selectionnee.</p>
                        <button class="nf-primary-button" @click="createBlankNote">
                            Creer ta premiere note
                        </button>
                    </div>
                </section>

                <aside class="nf-context-panel">
                    <section class="nf-section">
                        <div class="nf-section-head">
                            <h2>Linked notes</h2>
                            <span>{{ linkedNotes.length }}</span>
                        </div>
                        <div class="nf-context-list">
                            <button
                                v-for="item in linkedNotes"
                                :key="item.id"
                                class="nf-context-card"
                                @click="openLinkedNote(item.note_id, item.title)"
                            >
                                <small>{{ item.direction }}</small>
                                <strong>{{ item.title }}</strong>
                            </button>
                            <p v-if="!linkedNotes.length" class="nf-muted">
                                Ajoute des liens avec [[Nom de la note]].
                            </p>
                        </div>
                    </section>

                    <section class="nf-section">
                        <div class="nf-section-head">
                            <h2>Markdown</h2>
                        </div>
                        <ul class="nf-hints">
                            <li><code># titre</code></li>
                            <li><code>- liste</code></li>
                            <li><code>```code```</code></li>
                            <li><code>[[Note liee]]</code></li>
                            <li><code>#tag</code></li>
                        </ul>
                    </section>
                </aside>
            </div>
        </main>

        <div v-if="quickCaptureOpen" class="nf-modal-backdrop" @click.self="quickCaptureOpen = false">
            <div class="nf-modal">
                <div class="nf-section-head">
                    <h2>Quick capture</h2>
                    <button class="nf-text-button" @click="quickCaptureOpen = false">
                        Close
                    </button>
                </div>
                <textarea
                    v-model="quickCaptureValue"
                    class="nf-capture-input"
                    placeholder="Capture une idee, une tache, une reference..."
                    autofocus
                />
                <div class="nf-modal-actions">
                    <button class="nf-ghost-button" @click="quickCaptureOpen = false">
                        Cancel
                    </button>
                    <button class="nf-primary-button" @click="createQuickCapture">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
