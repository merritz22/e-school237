<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Models\User;

class TrackUserPresence
{
    const CACHE_KEY = 'online_users';
    const TTL       = 180; // 3 minutes en secondes

    public function handle(Request $request, Closure $next)
    {
        if (Auth::check()) {
            $userId    = Auth::id();
            $sessionId = session()->getId();

            $this->markOnline($userId, $sessionId);
        }

        return $next($request);
    }

    public static function markOnline(int $userId, string $sessionId): void
    {
        $onlineUsers = Cache::get(self::CACHE_KEY, []);

        // Structure : [userId => [sessionId => timestamp]]
        $onlineUsers[$userId][$sessionId] = now()->timestamp;

        Cache::put(self::CACHE_KEY, $onlineUsers, now()->addMinutes(5));
    }

    public static function markOffline(int $userId, string $sessionId): void
    {
        $onlineUsers = Cache::get(self::CACHE_KEY, []);

        // Supprimer la session de cet utilisateur
        unset($onlineUsers[$userId][$sessionId]);

        // Si plus aucune session active → retirer l'utilisateur
        if (empty($onlineUsers[$userId])) {
            unset($onlineUsers[$userId]);
        }

        Cache::put(self::CACHE_KEY, $onlineUsers, now()->addMinutes(5));
    }

    public static function getOnlineCount(): int
    {
        $onlineUsers = Cache::get(self::CACHE_KEY, []);
        $threshold   = now()->subMinutes(3)->timestamp;

        // Nettoyer les sessions expirées
        foreach ($onlineUsers as $userId => $sessions) {
            foreach ($sessions as $sessionId => $timestamp) {
                if ($timestamp < $threshold) {
                    unset($onlineUsers[$userId][$sessionId]);
                }
            }
            if (empty($onlineUsers[$userId])) {
                unset($onlineUsers[$userId]);
            }
        }

        // Mettre à jour le cache nettoyé
        Cache::put(self::CACHE_KEY, $onlineUsers, now()->addMinutes(5));

        return count($onlineUsers);
    }
}