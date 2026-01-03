<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class EvaluationSubject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'type',
        'subject_id',
        'level_id',
        'author_id',
        'duration_minutes',
        'difficulty',
        'file_path', 
        'file_size', 
        'file_type',
        'correction_file_path',
        'downloads_count',
    ];

    protected $casts = [
        'duration_minutes' => 'integer',
        'downloads_count' => 'integer',
        'level_id' => 'integer',
    ];

    // Relations

    /**
     * Author of the evaluation subject.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Subject evaluation subject.
     */
    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }

    /**
     * Level of the evaluation subject.
     */
    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class, 'level_id');
    }

    /**
     * Download logs.
     */
    public function downloadLogs(): MorphMany
    {
        return $this->morphMany(DownloadLog::class, 'resource');
    }

    // Scopes

    /**
     * Scope by type.
     */
    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope by subject.
     */
    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    /**
     * Scope by level.
     */
    public function scopeByLevel($query, $levelId)
    {
        return $query->where('level_id', $levelId);
    }

    /**
     * Scope by difficulty.
     */
    public function scopeByDifficulty($query, $difficulty)
    {
        return $query->where('difficulty', $difficulty);
    }

    /**
     * Scope by author.
     */
    public function scopeByAuthor($query, $authorId)
    {
        return $query->where('author_id', $authorId);
    }

    /**
     * Scope for popular evaluations.
     */
    public function scopePopular($query, $limit = 10)
    {
        return $query->orderByDesc('downloads_count')->limit($limit);
    }

    /**
     * Scope for recent evaluations.
     */
    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    // Helpers

    /**
     * Get formatted duration.
     */
    public function getFormattedDurationAttribute(): ?string
    {
        if (!$this->duration_minutes) {
            return null;
        }

        $hours = floor($this->duration_minutes / 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0) {
            return $minutes > 0 ? "{$hours}h {$minutes}min" : "{$hours}h";
        }

        return "{$minutes}min";
    }

    /**
     * Get difficulty badge color.
     */
    public function getDifficultyColorAttribute(): string
    {
        return match($this->difficulty) {
            'easy' => 'success',
            'medium' => 'warning',
            'hard' => 'danger',
            default => 'secondary'
        };
    }

    /**
     * Increment downloads count.
     */
    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }

    /**
     * Get evaluation URL.
     */
    public function getUrlAttribute(): string
    {
        return route('subjects.show', $this->id);
    }
}