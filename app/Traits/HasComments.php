<?php

namespace App\Traits;

use App\Models\Comment;
use Illuminate\Database\Eloquent\Relations\MorphMany;

trait HasComments
{
    public function comments(): MorphMany
    {
        return $this->morphMany(Comment::class, 'commentable')
                    ->whereNull('parent_id')
                    ->with(['user', 'replies.user', 'mentions', 'likes'])
                    ->orderByDesc('is_pinned')
                    ->orderByDesc('created_at');
    }
}