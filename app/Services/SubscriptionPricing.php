<?php

namespace App\Services;

use App\Enums\SubscriptionType;
use Carbon\Carbon;

class SubscriptionPricing
{
    /**
     * Détermine le type d'abonnement le plus proche d'un montant donné,
     * en comparant au catalogue configuré (config('subscriptions.plans')).
     *
     * Une correspondance "au plus proche" (plutôt que des plages fixes)
     * garantit qu'aucun montant ne reste sans type et évite tout
     * chevauchement entre formules.
     */
    public static function classify(float $price): SubscriptionType
    {
        $plans = config('subscriptions.plans');

        $closestType = null;
        $smallestDiff = null;

        foreach ($plans as $type => $catalogPrice) {
            $diff = abs($price - $catalogPrice);

            if ($smallestDiff === null || $diff < $smallestDiff) {
                $smallestDiff = $diff;
                $closestType = $type;
            }
        }

        return SubscriptionType::from($closestType);
    }

    /**
     * Période de couverture d'un abonnement : l'année scolaire camerounaise
     * (1er septembre → 30 juin), configurée dans config('subscriptions.school_year').
     *
     * @return array{0: Carbon, 1: Carbon}
     */
    public static function schoolYearRange(): array
    {
        $config = config('subscriptions.school_year');

        $startsAt = Carbon::create(now()->year, $config['start_month'], $config['start_day']);
        $endsAt = Carbon::create(now()->year + 1, $config['end_month'], $config['end_day']);

        return [$startsAt, $endsAt];
    }
}
