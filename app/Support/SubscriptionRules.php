<?php

namespace App\Support;

/**
 * Constantes de validation pour le module d'abonnement.
 *
 * Définies comme constantes de classe (et non via config()) car elles sont
 * référencées dans des attributs PHP #[Validate(...)] de composants Livewire,
 * qui exigent des expressions constantes au moment de la compilation.
 */
final class SubscriptionRules
{
    public const PHONE_REGEX = '/^6[0-9]{8}$/';
}
