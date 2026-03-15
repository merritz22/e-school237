<?php

namespace App\Services;

use App\Models\Notification;
use App\Models\UserNotification;
use App\Models\User;

class NotificationService
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function send(string $code, User $user, array $data = [])
    {
        $notification = Notification::where('code', $code)->first();

        if (!$notification) {
            return false;
        }

        // Vérifie si la notification pour cet utilisateur existe déjà
        $exists = UserNotification::where('notification_id', $notification->id)
            ->where('user_id', $user->id)
            ->whereNull('read_at')
            ->exists();

        if ($exists) {
            // Si elle existe déjà, ne rien créer
            return false;
        }
        
        return UserNotification::create([
            'user_id' => $user->id,
            'notification_id' => $notification->id,
            'data' => $data
        ]);
    }
}
