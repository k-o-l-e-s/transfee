<?php

use Illuminate\Support\Str;

return [
    'default' => env('API_EXCHANGERATES_NAME', 'Apilayer'),
    'data_providers' => [
        'Apilayer' => [
            'root_url' => env('API_EXCHANGERATES_URL'),
            'auth_key' => env('API_EXCHANGERATES_KEY'),
        ],
        'Apilayer2' => [
            'root_url' => env('API_EXCHANGERATES_URL'),
            'auth_key' => env('API_EXCHANGERATES_KEY'),
        ],
    ],
];
