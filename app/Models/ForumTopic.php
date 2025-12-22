<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ForumTopic extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'content',
        'author_id',
        'category_id',
        'is_pinned',
        'is_locked',
        'views_count',
        'replies_count',
        'last_reply_at',
    ];

    protected $casts = [
        'is_pinned' => 'boolean',
        'is_locked' => 'boolean',
        'views_count' => 'integer',
        'replies_count' => 'integer',
        'last_reply_at' => 'datetime',
    ];

    // Relations

    /**
     * Topic author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Topic category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Topic replies.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'topic_id');
    }

    /**
     * Approved replies only.
     */
    public function approvedReplies(): HasMany
    {
        return $this->replies()->where('is_approved', true);
    }

    // Scopes

    /**
     * Scope for pinned topics.
     */
    public function scopePinned($query)
    {
        return $query->where('is_pinned', true);
    }

    /**
     * Scope for not pinned topics.
     */
    public function scopeNotPinned($query)
    {
        return $query->where('is_pinned', false);
    }

    /**
     * Scope for locked topics.
     */
    public function scopeLocked($query)
    {
        return $query->where('is_locked', true);
    }

    /**
     * Scope for unlocked topics.
     */
    public function scopeUnlocked($query)
    {
        return $query->where('is_locked', false);
    }

    /**
     * Scope by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope by author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope for popular topics.
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderByDesc('replies_count')->limit($limit);
    }

    /**
     * Scope for recent topics.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    /**
     * Scope for active topics (recent replies).
     */
    public function scopeActive($query, $limit = 10)
    {
        return $query->orderByDesc('last_reply_at')->limit($limit);
    }

    // Helpers

    /**
     * Check if topic is pinned.
     */
    public function isPinned(): bool
    {
        return $this->is_pinned;
    }

    /**
     * Check if topic is locked.
     */
    public function isLocked(): bool
    {
        return $this->is_locked;
    }

    /**
     * Pin the topic.
     */
    public function pin(): void
    {
        $this->update(['is_pinned' => true]);
    }

    /**
     * Unpin the topic.
     */
    public function unpin(): void
    {
        $this->update(['is_pinned' => false]);
    }

    /**
     * Lock the topic.
     */
    public function lock(): void
    {
        $this->update(['is_locked' => true]);
    }

    /**
     * Unlock the topic.
     */
    public function unlock(): void
    {
        $this->update(['is_locked' => false]);
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Update reply statistics.
     */
    public function updateReplyStats(): void
    {
        $this->update([
            'replies_count' => $this->approvedReplies()->count(),
            'last_reply_at' => $this->approvedReplies()->latest()->first()?->created_at ?? $this->created_at,
        ]);
    }

    /**
     * Get topic URL.
     */
    public function getUrlAttribute(): string
    {
        return route('forum.topics.show', $this->id);
    }

    /**
     * Get last reply.
     */
    public function getLastReplyAttribute(): ?ForumReply
    {
        return $this->approvedReplies()->latest()->first();
    }
}