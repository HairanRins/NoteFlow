<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Services\NoteGraphService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SyncController extends Controller
{
    public function __construct(private readonly NoteGraphService $noteGraphService)
    {
    }

    public function __invoke(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'actions' => ['required', 'array'],
            'actions.*.type' => ['required', 'string', 'in:create,update,delete'],
            'actions.*.payload' => ['required', 'array'],
            'actions.*.payload.id' => ['nullable', 'string'],
            'actions.*.payload.title' => ['nullable', 'string', 'max:255'],
            'actions.*.payload.content' => ['nullable', 'string'],
        ]);

        $userId = $request->user()?->id;

        foreach ($validated['actions'] as $action) {
            $payload = $action['payload'];
            $note = isset($payload['id'])
                ? Note::query()->where('user_id', $userId)->find($payload['id'])
                : null;

            if ($action['type'] === 'create') {
                $this->noteGraphService->saveNote($note, $payload, $userId);
                continue;
            }

            if (! $note) {
                continue;
            }

            if ($action['type'] === 'update') {
                $this->noteGraphService->saveNote($note, $payload, $userId);
                continue;
            }

            $this->noteGraphService->deleteNote($note);
        }

        $notes = Note::query()
            ->where('user_id', $userId)
            ->with(['tags', 'outgoingLinks.targetNote', 'incomingLinks.sourceNote'])
            ->where('is_deleted', false)
            ->orderByDesc('updated_at')
            ->get();

        return response()->json([
            'ok' => true,
            'notes' => $notes,
            'synced_at' => now()->toISOString(),
        ]);
    }
}
