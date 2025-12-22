<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class ForumReply extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'topic_id',
        'author_id',
        'parent_reply_id',
        'likes_count',
        'is_approved',
    ];

    protected $casts = [
        'likes_count' => 'integer',
        'is_approved' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::created(function ($reply) {
            if ($reply->is_approved) {
                $reply->topic->updateReplyStats();
            }
        });

        static::updated(function ($reply) {
            if ($reply->wasChanged('is_approved')) {
                $reply->topic->updateReplyStats();
            }
        });

        static::deleted(function ($reply) {
            $reply->topic->updateReplyStats();
        });
    }

    // Relations

    /**
     * Reply author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Parent topic.
     */
    public function topic(): BelongsTo
    {
        return $this->belongsTo(ForumTopic::class, 'topic_id');
    }

    /**
     * Parent reply (for nested replies).
     */
    public function parentReply(): BelongsTo
    {
        return $this->belongsTo(ForumReply::class, 'parent_reply_id');
    }

    /**
     * Child replies.
     */
    public function childReplies(): HasMany
    {
        return $this->hasMany(ForumReply::class, 'parent_reply_id');
    }

    /**
     * Reply likes.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(UserLike::class, 'likeable');
    }

    // Scopes

    /**
     * Scope for approved replies.
     */
    public function scopeApproved($query)
    {
        return $query->where('is_approved', true);
    }

    /**
     * Scope for pending replies.
     */
    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    /**
     * Scope for top-level replies (no parent).
     */
    public function scopeTopLevel($query)
    {
        return $query->whereNull('parent_reply_id');
    }

    /**
     * Scope for nested replies.
     */
    public function scopeNested($query)
    {
        return $query->whereNotNull('parent_reply_id');
    }

    /**
     * Scope by topic.
     */
    public function scopeByTopic($query, $topicId)
    {
        return $query->where('topic_id', $topicId);
    }

    /**
     * Scope by author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope for popular replies.
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderByDesc('likes_count')->limit($limit);
    }

    // Helpers

    /**
     * Check if reply is approved.
     */
    public function isApproved(): bool
    {
        return $this->is_approved;
    }

    /**
     * Check if reply has parent.
     */
    public function hasParent(): bool
    {
        return !is_null($this->parent_reply_id);
    }

    /**
     * Check if reply has children.
     */
    public function hasChildren(): bool
    {
        return $this->childReplies()->exists();
    }

    /**
     * Approve the reply.
     */
    public function approve(): void
    {
        $this->update(['is_approved' => true]);
    }

    /**
     * Reject the reply.
     */
    public function reject(): void
    {
        $this->update(['is_approved' => false]);
    }

    /**
     * Increment likes count.
     */
    public function incrementLikes(): void
    {
        $this->increment('likes_count');
    }

    /**
     * Decrement likes count.
     */
    public function decrementLikes(): void
    {
        $this->decrement('likes_count');
    }

    /**
     * Get reply depth level.
     */
    public function getDepthAttribute(): int
    {
        $depth = 0;
        $parent = $this->parentReply;
        
        while ($parent) {
            $depth++;
            $parent = $parent->parentReply;
        }
        
        return $depth;
    }

    /**
     * Get reply URL.
     */
    public function getUrlAttribute(): string
    {
        return route('forum.topics.show', $this->topic_id) . '#reply-' . $this->id;
    }
}