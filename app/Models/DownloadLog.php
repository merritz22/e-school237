<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class DownloadLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'resource_type',
        'resource_id',
        'ip_address',
        'user_agent',
        'downloaded_at',
    ];

    protected $casts = [
        'downloaded_at' => 'datetime',
    ];

    public $timestamps = false;

    // Relations

    /**
     * User who downloaded (nullable for anonymous downloads).
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Downloaded resource (polymorphic).
     */
    public function resource(): MorphTo
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
     * Scope by resource type.
     */
    public function scopeByResourceType($query, $type)
    {
        return $query->where('resource_type', $type);
    }

    /**
     * Scope for authenticated downloads.
     */
    public function scopeAuthenticated($query)
    {
        return $query->whereNotNull('user_id');
    }

    /**
     * Scope for anonymous downloads.
     */
    public function scopeAnonymous($query)
    {
        return $query->whereNull('user_id');
    }

    /**
     * Scope for downloads in date range.
     */
    public function scopeDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('downloaded_at', [$startDate, $endDate]);
    }

    /**
     * Scope for recent downloads.
     */
    public function scopeRecent($query, $days = 30)
    {
        return $query->where('downloaded_at', '>=', now()->subDays($days));
    }

    // Helpers

    /**
     * Log a download.
     */
    public static function logDownload($resourceType, $resourceId, $userId = null, $ipAddress = null, $userAgent = null): void
    {
        static::create([
            'user_id' => $userId,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
            'ip_address' => $ipAddress ?? request()->ip(),
            'user_agent' => $userAgent ?? request()->userAgent(),
            'downloaded_at' => now(),
        ]);
    }

    /**
     * Get download statistics for a resource.
     */
    public static function getResourceStats($resourceType, $resourceId): array
    {
        $logs = static::where('resource_type', $resourceType)
                     ->where('resource_id', $resourceId);

        return [
            'total_downloads' => $logs->count(),
            'unique_users' => $logs->whereNotNull('user_id')->distinct('user_id')->count(),
            'anonymous_downloads' => $logs->whereNull('user_id')->count(),
            'last_downloaded' => $logs->max('downloaded_at'),
        ];
    }
}