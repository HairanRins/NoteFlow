const DB_NAME = 'noteflow-offline';
const DB_VERSION = 1;
const SNAPSHOT_STORE = 'snapshots';
const ACTION_STORE = 'actions';

function openDb() {
    return new Promise((resolve, reject) => {
        const request = indexedDB.open(DB_NAME, DB_VERSION);

        request.onupgradeneeded = () => {
            const db = request.result;

            if (!db.objectStoreNames.contains(SNAPSHOT_STORE)) {
                db.createObjectStore(SNAPSHOT_STORE);
            }

            if (!db.objectStoreNames.contains(ACTION_STORE)) {
                const actionStore = db.createObjectStore(ACTION_STORE, { keyPath: 'id' });
                actionStore.createIndex('created_at', 'created_at');
            }
        };

        request.onsuccess = () => resolve(request.result);
        request.onerror = () => reject(request.error);
    });
}

async function withStore(storeName, mode, callback) {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const transaction = db.transaction(storeName, mode);
        const store = transaction.objectStore(storeName);
        const result = callback(store);

        transaction.oncomplete = () => resolve(result);
        transaction.onerror = () => reject(transaction.error);
        transaction.onabort = () => reject(transaction.error);
    });
}

export async function saveSnapshot(key, value) {
    return withStore(SNAPSHOT_STORE, 'readwrite', (store) => store.put(value, key));
}

export async function loadSnapshot(key) {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const transaction = db.transaction(SNAPSHOT_STORE, 'readonly');
        const store = transaction.objectStore(SNAPSHOT_STORE);
        const request = store.get(key);

        request.onsuccess = () => resolve(request.result ?? null);
        request.onerror = () => reject(request.error);
    });
}

export async function queueAction(action) {
    return withStore(ACTION_STORE, 'readwrite', (store) => store.put(action));
}

export async function getQueuedActions() {
    const db = await openDb();

    return new Promise((resolve, reject) => {
        const transaction = db.transaction(ACTION_STORE, 'readonly');
        const store = transaction.objectStore(ACTION_STORE);
        const request = store.getAll();

        request.onsuccess = () => {
            const actions = request.result ?? [];
            actions.sort((a, b) => new Date(a.created_at) - new Date(b.created_at));
            resolve(actions);
        };
        request.onerror = () => reject(request.error);
    });
}

export async function clearQueuedActions(ids) {
    return withStore(ACTION_STORE, 'readwrite', (store) => {
        ids.forEach((id) => store.delete(id));
    });
}
