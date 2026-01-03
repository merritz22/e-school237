<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemException;
use Illuminate\Support\Facades\Log;

class EducationalResource extends Model
{
    use HasFactory;

    const MIME_TYPES_DOCUMENTS = [
        'application/pdf',
        // 'application/msword',
        // 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        // 'application/vnd.ms-excel',
        // 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        // 'application/vnd.ms-powerpoint',
        // 'application/vnd.openxmlformats-officedocument.presentationml.presentation',
        // 'text/plain',
        // 'text/csv',
    ];

    const MIME_TYPES_IMAGES = [
        // 'image/jpeg',
        // 'image/png',
        // 'image/gif',
        // 'image/svg+xml',
        // 'image/webp',
    ];

    protected $fillable = [
        'title',
        'description',
        'file_name',
        'file_path',
        'file_size',
        'file_type',
        'mime_type',
        'uploader_id',
        'category_id',
        'subject_id',
        'level_id',
        'downloads_count',
        'is_approved',
    ];

    protected $casts = [
        'file_size' => 'integer',
        'downloads_count' => 'integer',
        'is_approved' => 'boolean',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    protected $appends = [
        'formatted_file_size',
        'file_extension',
        'download_url',
        'url',
    ];

    // Relations

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function subject(): BelongsTo
    {
        return $this->belongsTo(Subject::class);
    }

    public function level(): BelongsTo
    {
        return $this->belongsTo(Level::class);
    }

    public function likes(): MorphMany
    {
        return $this->morphMany(UserLike::class, 'likeable');
    }

    public function downloadLogs(): MorphMany
    {
        return $this->morphMany(DownloadLog::class, 'resource');
    }

    // Scopes

    public function isPublished()
    {
        return $this->is_approved === true;
    }

    public function scopePending($query)
    {
        return $query->where('is_approved', false);
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeBySubject($query, $subjectId)
    {
        return $query->where('subject_id', $subjectId);
    }

    public function scopeByLevel($query, $levelId)
    {
        return $query->where('level_id', $levelId);
    }

    public function scopeByUploader($query, $uploaderId)
    {
        return $query->where('uploader_id', $uploaderId);
    }

    public function scopeByFileType($query, $fileType)
    {
        return $query->where('file_type', $fileType);
    }

    public function scopePopular($query, $limit = 10)
    {
        return $query->orderByDesc('downloads_count')->limit($limit);
    }

    public function scopeRecent($query, $limit = 10)
    {
        return $query->orderByDesc('created_at')->limit($limit);
    }

    public function scopeSearch($query, string $term)
    {
        return $query->where(function($q) use ($term) {
            $q->where('title', 'like', "%{$term}%")
              ->orWhere('description', 'like', "%{$term}%");
        });
    }

    // Helpers & Attributes

    public static function allowedMimeTypes(): array
    {
        return array_merge(
            self::MIME_TYPES_DOCUMENTS,
            self::MIME_TYPES_IMAGES
            // Ajoutez d'autres types si nécessaire
        );
    }

    public function getFormattedFileSizeAttribute(): string
    {
        $bytes = $this->file_size;
        
        if ($bytes === 0) {
            return '0 B';
        }

        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $base = log($bytes) / log(1024);
        
        return round(pow(1024, $base - floor($base)), 2) . ' ' . $units[floor($base)];
    }

    public function getFileExtensionAttribute(): string
    {
        return pathinfo($this->file_name, PATHINFO_EXTENSION);
    }

    public function isImage(): bool
    {
        return in_array($this->mime_type, self::MIME_TYPES_IMAGES);
    }

    public function isDocument(): bool
    {
        return in_array($this->mime_type, self::MIME_TYPES_DOCUMENTS);
    }

    public function getDownloadUrlAttribute(): string
    {
        return route('resources.download', $this);
    }

    public function getUrlAttribute(): string
    {
        return route('resources.show', $this);
    }

    public function canBeDownloadedBy(?User $user = null): bool
    {
        // Si la ressource est approuvée, accessible à tous
        if ($this->is_approved) {
            return true;
        }

        // Sinon, seul l'uploader peut y accéder
        return $user && $user->id === $this->uploader_id;
    }

    public function publishOrUnPublish(): bool
    {
        return $this->update(['is_approved' => !$this->is_approved,
            'published_at' => now(),
        ]);
    }

    public function reject(): bool
    {
        return $this->update(['is_approved' => false]);
    }

    public function incrementDownloads(): void
    {
        $this->timestamps = false; // Pour éviter de mettre à jour le champ updated_at
        $this->increment('downloads_count');
        $this->timestamps = true;
    }

    public function deleteFile(): bool
    {
        if (empty($this->file_path)) {
            return false;
        }

        try {
            return Storage::delete($this->file_path);
        } catch (FilesystemException $e) {
            Log::error("Failed to delete educational resource file: {$this->file_path}", [
                'error' => $e->getMessage(),
                'resource_id' => $this->id
            ]);
            return false;
        }
    }

    public function getFileUrl(): ?string
    {
        if (empty($this->file_path)) {
            return null;
        }

        try {
            return Storage::url($this->file_path);
        } catch (FilesystemException $e) {
            Log::error("Failed to generate URL for educational resource file: {$this->file_path}", [
                'error' => $e->getMessage(),
                'resource_id' => $this->id
            ]);
            return null;
        }
    }
}