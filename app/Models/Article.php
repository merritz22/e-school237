<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Str;

class Article extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'excerpt',
        'featured_image',
        'author_id',
        'category_id',
        'status',
        'views_count',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
        'views_count' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();
        
        static::creating(function ($article) {
            if (empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
            if (empty($article->excerpt)) {
                $article->excerpt = Str::limit(strip_tags($article->content), 200);
            }
        });

        static::updating(function ($article) {
            if ($article->isDirty('title') && empty($article->slug)) {
                $article->slug = Str::slug($article->title);
            }
        });
    }

    // Relations

    /**
     * Get reading time estimate in minutes.
     */
    public function getReadingTimeAttribute(): int
    {
        $words = str_word_count(strip_tags($this->content));
        return max(1, ceil($words / 200)); // Average reading speed: 200 words per minute
    }

    /**
     * Get the article's URL.
     */
    public function getUrlAttribute(): string
    {
        return route('articles.show', $this->slug);
    }

    /**
     * Publish the article.
     */
    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }
    /* Article author.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Article category.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Article tags.
     */
    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class, 'article_tags');
    }

    /**
     * Article likes.
     */
    public function likes(): MorphMany
    {
        return $this->morphMany(UserLike::class, 'likeable');
    }

    // Scopes

    /**
     * Scope for published articles.
     */
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
                    ->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for draft articles.
     */
    public function scopeDraft($query)
    {
        return $query->where('status', 'draft');
    }

    /**
     * Scope for articles by status.
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for articles by author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope for articles by category.
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Scope for popular articles (most viewed).
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderByDesc('views_count')->limit($limit);
    }

    /**
     * Scope for recent articles.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('published_at')->limit($limit);
    }

    // Helpers

    /**
     * Check if article is published.
     */
    public function isPublished(): bool
    {
        return $this->status === 'published' 
               && $this->published_at 
               && $this->published_at <= now();
    }

    /**
     * Check if article is draft.
     */
    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    /**
     * Increment views count.
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * nombres de like
     */
    public function isLikedByUser()
    {
        // $this->authorize('create', Article::class);
        return 1;
    }

    /**
     * nombres de signet
     */
    public function isBookmarkedByUser()
    {
        // $this->authorize('create', Article::class);
        return 1;
    }

    /**
     * Récupère l'article précédent (par date de publication).
     *
     * @return Article|null
     */
    public function getPreviousArticle()
    {
        return self::where('status', 'published') // On prend seulement les articles publiés
            ->where('published_at', '<', $this->published_at) // Dont la date est avant celle de l'article actuel
            ->orderBy('published_at', 'desc') // On prend le plus proche en date avant
            ->first(); // Retourne le premier trouvé (ou null si aucun)
    }

    /**
     * Récupère l'article suivant (par date de publication).
     *
     * @return Article|null
     */
    public function getNextArticle()
    {
        return self::where('status', 'published') // On prend seulement les articles publiés
            ->where('published_at', '>', $this->published_at) // Dont la date est après celle de l'article actuel
            ->orderBy('published_at', 'asc') // On prend le plus proche en date après
            ->first(); // Retourne le premier trouvé (ou null si aucun)
    }

}
