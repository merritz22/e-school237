<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'orange' => [
        'base_url' => env('ORANGE_BASE_URL', 'https://sandbox.orange.com'), // URL API
        'token' => env('ORANGE_TOKEN'), // Token OAuth/API
        'secret' => env('ORANGE_SECRET'), // clé pour signature webhook
    ],

    'mtn' => [
        'base_url' => env('MTN_BASE_URL'), // URL API
        'env' => env('MTN_ENV'),
        'api_user' => env('MTN_API_USER'),
        'api_key' => env('MTN_API_KEY'),
        'subscription_key' => env('MTN_SUBSCRIPTION_KEY'), 
    ],

];
