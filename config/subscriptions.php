<?php

use App\Support\SubscriptionRules;

return [

    /*
    |--------------------------------------------------------------------------
    | Devise
    |--------------------------------------------------------------------------
    */
    'currency' => 'XAF',

    // Indicatif téléphonique utilisé pour construire les liens wa.me
    'country_code' => '237',

    /*
    |--------------------------------------------------------------------------
    | Catalogue des formules
    |--------------------------------------------------------------------------
    |
    | Source unique de vérité pour les prix des abonnements (en XAF/an).
    | Toute modification ici se répercute sur la page tarifs, le formulaire
    | d'achat et la classification automatique du type d'abonnement.
    |
    */
    'plans' => [
        'CLASSIC' => 5000,
        'PREMIUM' => 12000,
        'ADVANCED' => 16000,
    ],

    // Prix barré affiché sur l'offre Premium (remise promotionnelle affichée)
    'premium_original_price' => 15000,

    /*
    |--------------------------------------------------------------------------
    | Année scolaire camerounaise
    |--------------------------------------------------------------------------
    |
    | Un abonnement couvre la période allant du 1er septembre au 30 juin.
    |
    */
    'school_year' => [
        'start_month' => 9,
        'start_day' => 1,
        'end_month' => 6,
        'end_day' => 30,
    ],

    /*
    |--------------------------------------------------------------------------
    | Téléphone
    |--------------------------------------------------------------------------
    */
    'phone_regex' => SubscriptionRules::PHONE_REGEX,

    /*
    |--------------------------------------------------------------------------
    | Comptes de dépôt Mobile Money
    |--------------------------------------------------------------------------
    */
    'payment_accounts' => [
        'orange' => [
            'number' => env('ORANGE_MONEY_ACCOUNT_NUMBER', '696090236'),
            'holder' => env('PAYMENT_ACCOUNT_HOLDER', 'POUOKAM NGUEGUIM'),
        ],
        'mtn' => [
            'number' => env('MTN_MOMO_ACCOUNT_NUMBER', '651993749'),
            'holder' => env('PAYMENT_ACCOUNT_HOLDER', 'POUOKAM NGUEGUIM'),
        ],
    ],

    // Délai annoncé à l'utilisateur pour la validation manuelle de son paiement
    'activation_delay_hours' => 12,

    /*
    |--------------------------------------------------------------------------
    | Webhook Orange Money
    |--------------------------------------------------------------------------
    |
    | IP autorisées à appeler le callback Orange. Laisser vide désactive le
    | contrôle IP (le callback reste protégé par la vérification de signature).
    | Renseigner via ORANGE_WEBHOOK_IPS="1.2.3.4,5.6.7.8" une fois les IP
    | officielles communiquées par Orange.
    |
    */
    'orange_webhook_ips' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('ORANGE_WEBHOOK_IPS', ''))
    ))),

];
