<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Services\NoteGraphService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function __construct(private readonly NoteGraphService $noteGraphService)
    {
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
        ]);

        $note = $this->noteGraphService->saveNote(
            null,
            $data,
            $request->user()?->id,
        );

        return response()->json(['note' => $note], 201);
    }

    public function show(Request $request, string $id): JsonResponse
    {
        return response()->json([
            'note' => $this->resolveNote($request, $id)->load([
                'tags',
                'outgoingLinks.targetNote',
                'incomingLinks.sourceNote',
            ]),
        ]);
    }

    public function update(Request $request, string $id): JsonResponse
    {
        $data = $request->validate([
            'title' => ['nullable', 'string', 'max:255'],
            'content' => ['nullable', 'string'],
            'is_deleted' => ['nullable', 'boolean'],
        ]);

        $note = $this->noteGraphService->saveNote(
            $this->resolveNote($request, $id),
            $data,
            $request->user()?->id,
        );

        return response()->json(['note' => $note]);
    }

    public function destroy(Request $request, string $id): JsonResponse
    {
        $note = $this->noteGraphService->deleteNote($this->resolveNote($request, $id));

        return response()->json(['note' => $note]);
    }

    private function resolveNote(Request $request, string $id): Note
    {
        return Note::query()
            ->where('user_id', $request->user()?->id)
            ->where('id', $id)
            ->firstOrFail();
    }
}
