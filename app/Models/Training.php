<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class Training extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'banner',
        'duration',
        'price',
        'original_price',
        'technical_prerequisites',
        'intellectual_prerequisites',
        'status',
        'published_at',
        'created_by',
    ];

    protected $casts = [
        'price' => 'integer',
        'original_price' => 'integer',
        'published_at' => 'datetime',
    ];

    protected $appends = [
        'banner_url',
        'url',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function (Training $training) {
            if (empty($training->slug)) {
                $training->slug = Str::slug($training->title);
            }
        });

        static::updating(function (Training $training) {
            if ($training->isDirty('title') && empty($training->slug)) {
                $training->slug = Str::slug($training->title);
            }
        });
    }

    // Relations

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Scopes

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    // Helpers & Attributes

    public function isPublished(): bool
    {
        return $this->status === 'published';
    }

    public function isDisabled(): bool
    {
        return $this->status === 'disabled';
    }

    public function isDraft(): bool
    {
        return $this->status === 'draft';
    }

    public function publish(): void
    {
        $this->update([
            'status' => 'published',
            'published_at' => now(),
        ]);
    }

    public function disable(): void
    {
        $this->update(['status' => 'disabled']);
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->banner ? Storage::disk('public')->url($this->banner) : null;
    }

    public function getUrlAttribute(): string
    {
        return route('trainings.show', $this->slug);
    }

    public function getDiscountPercentAttribute(): ?int
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return null;
        }

        return (int) round((($this->original_price - $this->price) / $this->original_price) * 100);
    }

    public function deleteBanner(): void
    {
        if ($this->banner) {
            Storage::disk('public')->delete($this->banner);
        }
    }
}
