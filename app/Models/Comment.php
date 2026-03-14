<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\{BelongsTo, HasMany, BelongsToMany, MorphTo};

class Comment extends Model
{
    protected $fillable = [
        'user_id', 'commentable_type', 'commentable_id',
        'body', 'parent_id', 'is_deleted', 'deleted_display',
        'deleted_at', 'is_approved', 'is_pinned', 'likes_count',
    ];

    protected $casts = [
        'is_deleted'  => 'boolean',
        'is_approved' => 'boolean',
        'is_pinned'   => 'boolean',
        'deleted_at'  => 'datetime',
    ];

    // Relation polymorphique
    public function commentable(): MorphTo
    {
        return $this->morphTo();
    }

    // Auteur
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Parent (pour les réponses)
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    // Réponses
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_id')
                    ->with(['user', 'mentions', 'likes'])
                    ->orderBy('created_at', 'asc');
    }

    // Mentions
    public function mentions(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_mentions');
    }

    // Likes
    public function likes(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'comment_likes');
    }

    // Helper : est-ce que l'user courant a liké ?
    public function isLikedBy(?User $user): bool
    {
        if (!$user) return false;
        return $this->likes->contains($user->id);
    }

    // Suppression logique
    public function softDeleteComment(string $display = 'blurred'): void
    {
        $this->update([
            'is_deleted'      => true,
            'deleted_display' => $display,
            'deleted_at'      => now(),
        ]);
    }

    // Extraire les mentions du texte (@username)
    public static function extractMentions(string $body): array
    {
        preg_match_all('/@([\w]+)/', $body, $matches);
        return $matches[1] ?? [];
    }
}