<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Note;
use App\Models\Tag;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkspaceController extends Controller
{
    public function __invoke(Request $request): JsonResponse
    {
        $query = trim((string) $request->string('query', ''));
        $tag = trim((string) $request->string('tag', ''));
        $includeDeleted = $request->boolean('include_deleted', false);
        $userId = $request->user()?->id;

        $notesQuery = Note::query()
            ->where('user_id', $userId)
            ->with(['tags', 'outgoingLinks.targetNote', 'incomingLinks.sourceNote'])
            ->when(! $includeDeleted, fn ($builder) => $builder->where('is_deleted', false))
            ->when($query !== '', function ($builder) use ($query) {
                $builder->where(function ($nested) use ($query) {
                    $nested
                        ->where('title', 'ilike', "%{$query}%")
                        ->orWhere('content', 'ilike', "%{$query}%")
                        ->orWhereHas('tags', fn ($tagQuery) => $tagQuery->where('name', 'ilike', "%{$query}%"));
                });
            })
            ->when($tag !== '', fn ($builder) => $builder->whereHas('tags', fn ($tagQuery) => $tagQuery->where('name', $tag)))
            ->orderByDesc('updated_at');

        $notes = $notesQuery->get();

        $tags = Tag::query()
            ->where('user_id', $userId)
            ->withCount(['notes' => fn ($builder) => $builder->where('is_deleted', false)])
            ->orderBy('name')
            ->get();

        return response()->json([
            'notes' => $notes->map(fn (Note $note) => $this->transformNote($note))->values(),
            'tags' => $tags->map(fn (Tag $workspaceTag) => [
                'id' => $workspaceTag->id,
                'name' => $workspaceTag->name,
                'count' => $workspaceTag->notes_count,
            ])->values(),
            'meta' => [
                'query' => $query,
                'tag' => $tag,
                'generated_at' => now()->toISOString(),
            ],
        ]);
    }

    private function transformNote(Note $note): array
    {
        return [
            'id' => $note->id,
            'title' => $note->title,
            'content' => $note->content,
            'is_deleted' => $note->is_deleted,
            'created_at' => $note->created_at?->toISOString(),
            'updated_at' => $note->updated_at?->toISOString(),
            'tags' => $note->tags->map(fn ($tag) => [
                'id' => $tag->id,
                'name' => $tag->name,
            ])->values(),
            'outgoing_links' => $note->outgoingLinks
                ->filter(fn ($link) => $link->targetNote !== null)
                ->map(fn ($link) => [
                    'id' => $link->id,
                    'target_note_id' => $link->target_note_id,
                    'target_note_title' => $link->targetNote->title,
                ])->values(),
            'incoming_links' => $note->incomingLinks
                ->filter(fn ($link) => $link->sourceNote !== null)
                ->map(fn ($link) => [
                    'id' => $link->id,
                    'source_note_id' => $link->source_note_id,
                    'source_note_title' => $link->sourceNote->title,
                ])->values(),
        ];
    }
}
