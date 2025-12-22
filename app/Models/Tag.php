<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'usage_count',
    ];

    protected $casts = [
        'usage_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($tag) {
            if (empty($tag->slug)) {
                $tag->slug = Str::slug($tag->name);
            }
        });
    }

    // Relations

    /**
     * Articles with this tag.
     */
    public function articles(): BelongsToMany
    {
        return $this->belongsToMany(Article::class, 'article_tags');
    }

    // Scopes

    /**
     * Scope for popular tags.
     */
    public function scopePopular($query, $limit = 20)
    {
        return $query->orderByDesc('usage_count')->limit($limit);
    }

    /**
     * Scope for tags with minimum usage.
     */
    public function scopeWithMinimumUsage($query, $minUsage = 1)
    {
        return $query->where('usage_count', '>=', $minUsage);
    }

    // Helpers

    /**
     * Increment usage count.
     */
    public function incrementUsage(): void
    {
        $this->increment('usage_count');
    }

    /**
     * Decrement usage count.
     */
    public function decrementUsage(): void
    {
        $this->decrement('usage_count');
    }

    /**
     * Get tag URL.
     */
    public function getUrlAttribute(): string
    {
        return route('tags.show', $this->slug);
    }
}