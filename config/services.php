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

    'firebase' => [
        'credentials' => env('FIREBASE_CREDENTIALS'),
        'database_url' => env('FIREBASE_DATABASE_URL'),
        'storage_bucket' => env('FIREBASE_STORAGE_BUCKET'),
        'project_id' => env('FIREBASE_PROJECT_ID'),
    ],

    'fcm' => [
        'url' => env('FCM_URL', 'https://fcm.googleapis.com/fcm/send'),
        'server_key' => env('FCM_SERVER_KEY'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'resend' => [
        'key' => env('RESEND_KEY'),
    ],

    'slack' => [
        'notifications' => [
            'bot_user_oauth_token' => env('SLACK_BOT_USER_OAUTH_TOKEN'),
            'channel' => env('SLACK_BOT_USER_DEFAULT_CHANNEL'),
        ],
    ],

    'hyperpay' => [
        'access_token' => env('HYPERPAY_ACCESS_TOKEN', 'OGFjN2E0Yzc5NDgzMDkyNjAxOTQ4MzY2MzY1ZDAxMTZ8NnpwP1Q9Y3dGTiUyYWN6NmFmQWo='),
        'entity_id' => env('HYPERPAY_ENTITY_ID', '8ac7a4c79483092601948366b9d1011b'),
        'base_url' => env('HYPERPAY_BASE_URL', 'https://eu-test.oppwa.com/'),
        'currency' => env('HYPERPAY_CURRENCY', 'SAR')
    ],

    'tap' => [
        'secret_key' => env('TAP_SECRET_KEY'),
        'public_key' => env('TAP_PUBLIC_KEY'),
        'base_url' => env('TAP_BASE_URL', 'https://api.tap.company/v2/'),
        'redirect_url' => env('TAP_REDIRECT_URL'),
        'wallet_callback_url' => env('WALLET_CALLBACK_URL'),
        'currency' => env('TAP_CURRENCY', 'SAR'),
        'merchant_id' => env('TAP_MERCHANT_ID')
    ]

];
