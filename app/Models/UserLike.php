<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class UserLike extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'likeable_type',
        'likeable_id',
    ];

    // Relations

    /**
     * User who liked.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Likeable item (polymorphic).
     */
    public function likeable(): MorphTo
    {
        return $this->morphTo();
    }

    // Scopes

    /**
     * Scope by user.
     */
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope by likeable type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('likeable_type', $type);
    }

    // Helpers

    /**
     * Check if user has liked specific item.
     */
    public static function hasLiked($userId, $likeableType, $likeableId): bool
    {
        return static::where([
            'user_id' => $userId,
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId,
        ])->exists();
    }

    /**
     * Toggle like for user and item.
     */
    public static function toggle($userId, $likeableType, $likeableId): bool
    {
        $like = static::where([
            'user_id' => $userId,
            'likeable_type' => $likeableType,
            'likeable_id' => $likeableId,
        ])->first();

        if ($like) {
            $like->delete();
            return false; // unliked
        } else {
            static::create([
                'user_id' => $userId,
                'likeable_type' => $likeableType,
                'likeable_id' => $likeableId,
            ]);
            return true; // liked
        }
    }
}