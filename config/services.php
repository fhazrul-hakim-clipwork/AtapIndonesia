<?php

return [
    'google' => [
        'client_id' => env('GOOGLE_CLIENT_ID'),
        'client_secret' => env('GOOGLE_CLIENT_SECRET'),
        'redirect_url' => env('GOOGLE_REDIRECT_URL', env('APP_URL') . '/auth/google/callback'),
    ],

    'lalamove' => [
        'key' => env('LALAMOVE_KEY'),
        'secret' => env('LALAMOVE_SECRET'),
        'sandbox' => env('LALAMOVE_SANDBOX', true),
        'base_url' => env('LALAMOVE_SANDBOX', true)
            ? 'https://sandbox.lalamove.com'
            : 'https://api.lalamove.com',
    ],

    'deliveree' => [
        'key' => env('DELIVEREE_KEY'),
        'base_url' => env('DELIVEREE_BASE_URL', 'https://api.deliveree.com'),
    ],
];
