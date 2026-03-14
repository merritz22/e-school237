<?php

namespace App\Console\Commands;

use App\Jobs\SendEmailVerificationReminderJob;
use App\Mail\UnverifiedUsersReportMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendEmailVerificationReminders extends Command
{
    protected $signature = 'app:send-verification-reminders';
    protected $description = 'Envoie un rappel de vérification email aux utilisateurs non vérifiés';

    public function handle(): void
    {
        $users = User::whereNull('email_verified_at')->get();

        if ($users->isEmpty()) {
            $this->info('✅ Tous les utilisateurs ont vérifié leur email.');
            return;
        }

        $this->info("📧 Envoi des rappels à {$users->count()} utilisateur(s)...");

        foreach ($users as $user) {
            try {
                SendEmailVerificationReminderJob::dispatch($user);
                $this->info("✓ Rappel envoyé à : {$user->email}");
            } catch (\Throwable $e) {
                Log::error("Échec rappel vérification pour {$user->email} : " . $e->getMessage());
                $this->error("✗ Échec pour : {$user->email}");
            }
        }

        // Rapport admin
        try {
            Mail::to('admin@e-school237.com')
                ->send(new UnverifiedUsersReportMail($users, $users->count()));

            $this->info("📊 Rapport envoyé à admin@e-school237.com");
        } catch (\Throwable $e) {
            Log::error("Échec envoi rapport admin : " . $e->getMessage());
            $this->error("✗ Échec rapport admin : " . $e->getMessage());
        }

        $this->info("✅ Terminé !");
    }
}