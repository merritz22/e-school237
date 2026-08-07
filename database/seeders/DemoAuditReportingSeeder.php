<?php

namespace Database\Seeders;

use App\Models\AuditLog;
use App\Models\DownloadLog;
use App\Models\EducationalResource;
use App\Models\EvaluationSubject;
use App\Models\Level;
use App\Models\LoginHistory;
use App\Models\Subscription;
use App\Models\User;
use App\Services\SubscriptionPricing;
use Illuminate\Database\Seeder;

/**
 * Jeu de données de démonstration pour tester le journal d'audit,
 * l'historique de connexion et les rapports (revenus/croissance/engagement).
 * Ajoute 10 enregistrements dans chacune des tables concernées, avec des
 * dates étalées sur les derniers mois pour que les graphiques aient une
 * allure réaliste (et pas un pic unique "aujourd'hui").
 */
class DemoAuditReportingSeeder extends Seeder
{
    public function run(): void
    {
        $members = $this->seedMembers();

        $this->seedSubscriptions($members);
        $this->seedDownloadLogs($members);
        $this->seedLoginHistories($members);
        $this->seedAuditLogs($members);

        $this->command->info('10 utilisateurs, 10 abonnements, 10 téléchargements, 10 connexions et 10 entrées d\'audit ajoutés.');
    }

    private function seedMembers()
    {
        return collect(range(1, 10))->map(function () {
            $user = User::factory()->member()->create();

            // created_at n'est pas mass-assignable (protection normale) : on le
            // recale après coup pour étaler les inscriptions sur 6 mois.
            $user->forceFill(['created_at' => now()->subDays(random_int(0, 180))])->save();

            return $user;
        });
    }

    private function seedSubscriptions($members): void
    {
        $levels = Level::where('is_active', 1)->get();
        $admin = User::where('role', 'admin')->first();
        $plans = config('subscriptions.plans');
        $statuses = ['active', 'active', 'active', 'pending', 'cancelled'];

        foreach ($members as $i => $member) {
            $type = array_rand($plans);
            $price = $plans[$type];
            $status = $statuses[$i % count($statuses)];

            [$startsAt, $endsAt] = SubscriptionPricing::schoolYearRange();
            $validatedAt = $status === 'active' ? now()->subDays(random_int(1, 180)) : null;

            $subscription = Subscription::create([
                'user_id' => $member->id,
                'level_id' => $levels->random()->id,
                'subject_id' => null,
                'starts_at' => $startsAt,
                'ends_at' => $endsAt,
                'status' => $status,
                'amount' => $price,
                'currency' => config('subscriptions.currency'),
                'phone' => '6' . random_int(70000000, 99999999),
                'type' => $type,
                'validated_at' => $validatedAt,
                'validated_by' => $status === 'active' ? $admin?->id : null,
            ]);

            $subscription->forceFill(['created_at' => $validatedAt ?? now()->subDays(random_int(1, 30))])->save();
        }
    }

    private function seedDownloadLogs($members): void
    {
        $resourceIds = EducationalResource::pluck('id');
        $evaluationIds = EvaluationSubject::pluck('id');

        for ($i = 0; $i < 10; $i++) {
            $isResource = $resourceIds->isNotEmpty() && ($evaluationIds->isEmpty() || random_int(0, 1) === 1);

            DownloadLog::create([
                'user_id' => $members->random()->id,
                'resource_type' => $isResource ? 'resource' : 'evaluation',
                'resource_id' => $isResource ? $resourceIds->random() : $evaluationIds->random(),
                'ip_address' => random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254),
                'user_agent' => 'Mozilla/5.0 (Demo Seeder)',
                'downloaded_at' => now()->subDays(random_int(0, 180))->subHours(random_int(0, 23)),
            ]);
        }
    }

    private function seedLoginHistories($members): void
    {
        for ($i = 0; $i < 10; $i++) {
            $success = random_int(1, 100) <= 85; // 85% de connexions réussies
            $user = $members->random();

            $login = LoginHistory::create([
                'user_id' => $success ? $user->id : null,
                'email' => $user->email,
                'status' => $success ? 'success' : 'failed',
                'provider' => random_int(1, 100) <= 20 ? 'google' : 'password',
                'ip_address' => random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254) . '.' . random_int(1, 254),
                'user_agent' => 'Mozilla/5.0 (Demo Seeder)',
            ]);

            $login->forceFill(['created_at' => now()->subDays(random_int(0, 60))->subHours(random_int(0, 23))])->save();
        }
    }

    private function seedAuditLogs($members): void
    {
        $admin = User::where('role', 'admin')->first();

        $actions = [
            ['action' => 'user.suspended', 'description' => 'Suspension de compte (démo)'],
            ['action' => 'user.activated', 'description' => 'Réactivation de compte (démo)'],
            ['action' => 'subscription.validated', 'description' => "Validation manuelle d'un abonnement (démo)"],
            ['action' => 'resource.approved', 'description' => "Approbation d'une ressource (démo)"],
            ['action' => 'resource.status_changed', 'description' => 'Publication/dépublication de support (démo)'],
            ['action' => 'article.status_changed', 'description' => "Changement de statut d'article (démo)"],
            ['action' => 'subject.status_changed', 'description' => 'Activation/désactivation de matière (démo)'],
            ['action' => 'level.status_changed', 'description' => 'Activation/désactivation de classe (démo)'],
            ['action' => 'user.role_changed', 'description' => 'Changement de rôle utilisateur (démo)'],
            ['action' => 'user.created', 'description' => "Création d'utilisateur par un admin (démo)"],
        ];

        foreach ($actions as $entry) {
            $target = $members->random();

            $log = AuditLog::create([
                'user_id' => $admin?->id,
                'action' => $entry['action'],
                'description' => $entry['description'] . " — cible : {$target->name}",
                'auditable_type' => User::class,
                'auditable_id' => $target->id,
                'old_values' => ['status' => 'before'],
                'new_values' => ['status' => 'after'],
                'ip_address' => '127.0.0.1',
                'user_agent' => 'Mozilla/5.0 (Demo Seeder)',
            ]);

            $log->forceFill(['created_at' => now()->subDays(random_int(0, 90))->subHours(random_int(0, 23))])->save();
        }
    }
}
