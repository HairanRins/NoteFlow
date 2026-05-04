<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Note extends Model
{
    use HasFactory;
    use HasUuids;

    protected $fillable = [
        'user_id',
        'title',
        'content',
        'is_deleted',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected function casts(): array
    {
        return [
            'is_deleted' => 'boolean',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class)->withTimestamps();
    }

    public function outgoingLinks(): HasMany
    {
        return $this->hasMany(NoteLink::class, 'source_note_id');
    }

    public function incomingLinks(): HasMany
    {
        return $this->hasMany(NoteLink::class, 'target_note_id');
    }
}
