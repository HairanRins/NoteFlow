<?php

namespace App\Services;

use App\Models\Note;
use App\Models\NoteLink;
use App\Models\Tag;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class NoteGraphService
{
    public function saveNote(?Note $note, array $attributes, ?int $userId = null): Note
    {
        $note ??= new Note();

        $title = trim((string) ($attributes['title'] ?? ''));
        $content = (string) ($attributes['content'] ?? '');

        $note->fill([
            'user_id' => $userId,
            'title' => $title !== '' ? $title : $this->titleFromContent($content),
            'content' => $content,
            'is_deleted' => (bool) ($attributes['is_deleted'] ?? false),
        ]);
        $note->save();

        $this->syncTags($note, $userId);
        $this->syncLinks($note, $userId);

        return $note->fresh([
            'tags',
            'outgoingLinks.targetNote',
            'incomingLinks.sourceNote',
        ]);
    }

    public function deleteNote(Note $note): Note
    {
        $note->forceFill(['is_deleted' => true])->save();

        return $note->fresh([
            'tags',
            'outgoingLinks.targetNote',
            'incomingLinks.sourceNote',
        ]);
    }

    public function extractTags(string $content): array
    {
        preg_match_all('/(^|\s)#([A-Za-z0-9\-_]+)/u', $content, $matches);

        return collect($matches[2] ?? [])
            ->map(fn ($tag) => Str::lower(trim($tag)))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function extractLinkedTitles(string $content): array
    {
        preg_match_all('/\[\[([^\[\]]+)\]\]/u', $content, $matches);

        return collect($matches[1] ?? [])
            ->map(fn ($title) => trim($title))
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    private function syncTags(Note $note, ?int $userId): void
    {
        $tagIds = collect($this->extractTags($note->content))
            ->map(function (string $tagName) use ($userId) {
                return Tag::query()->firstOrCreate(
                    ['user_id' => $userId, 'name' => $tagName],
                    ['name' => $tagName],
                )->id;
            })
            ->all();

        $note->tags()->sync($tagIds);
    }

    private function syncLinks(Note $note, ?int $userId): void
    {
        $targets = collect($this->extractLinkedTitles($note->content))
            ->map(fn (string $title) => $this->findOrCreateLinkedNote($title, $userId, $note->id))
            ->filter(fn (Note $linked) => $linked->id !== $note->id)
            ->values();

        NoteLink::query()->where('source_note_id', $note->id)->delete();

        $targets->each(function (Note $linked) use ($note) {
            NoteLink::query()->create([
                'source_note_id' => $note->id,
                'target_note_id' => $linked->id,
            ]);
        });
    }

    private function findOrCreateLinkedNote(string $title, ?int $userId, string $currentNoteId): Note
    {
        $normalizedTitle = trim($title);

        $existing = Note::query()
            ->where('user_id', $userId)
            ->where('id', '!=', $currentNoteId)
            ->whereRaw('LOWER(title) = ?', [Str::lower($normalizedTitle)])
            ->first();

        if ($existing) {
            return $existing;
        }

        return Note::query()->create([
            'user_id' => $userId,
            'title' => $normalizedTitle !== '' ? $normalizedTitle : 'Untitled',
            'content' => '',
            'is_deleted' => false,
        ]);
    }

    private function titleFromContent(string $content): string
    {
        $line = collect(preg_split("/\r\n|\n|\r/", $content) ?: [])
            ->map(fn ($value) => trim((string) $value))
            ->first(fn ($value) => $value !== '');

        return $line ? Str::limit(ltrim($line, '#- '), 80, '') : 'Untitled';
    }
}
