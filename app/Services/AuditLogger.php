<?php

namespace App\Services;

use App\Models\AuditLog;
use App\Models\LoginHistory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditLogger
{
    /**
     * Enregistre une action administrative (qui a fait quoi, sur quoi, avant/après).
     */
    public static function log(
        string $action,
        ?string $description = null,
        ?Model $auditable = null,
        array $oldValues = [],
        array $newValues = [],
        ?int $userId = null
    ): AuditLog {
        return AuditLog::create([
            'user_id' => $userId ?? Auth::id(),
            'action' => $action,
            'description' => $description,
            'auditable_type' => $auditable ? $auditable->getMorphClass() : null,
            'auditable_id' => $auditable?->getKey(),
            'old_values' => $oldValues ?: null,
            'new_values' => $newValues ?: null,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::header('User-Agent'), 0, 255),
        ]);
    }

    /**
     * Enregistre une tentative de connexion (réussie ou échouée).
     */
    public static function logLogin(string $status, ?int $userId, string $email, string $provider = 'password'): LoginHistory
    {
        return LoginHistory::create([
            'user_id' => $userId,
            'email' => $email,
            'status' => $status,
            'provider' => $provider,
            'ip_address' => Request::ip(),
            'user_agent' => substr((string) Request::header('User-Agent'), 0, 255),
        ]);
    }
}
