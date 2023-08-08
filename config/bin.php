<?php

use Illuminate\Support\Str;

return [

    'default' => env('API_BIN_NAME', 'Binlist'),

    'data_providers' => [
        'Binlist' => [
            'root_url' => env('API_BIN_URL'),
            'auth_key_name' => null,
            'call_bin_country' => env('API_BIN_URL')."/%{transaction_bin}",
            'bin_country_json_path' => 'country.alpha2',
        ],
    ],
];
