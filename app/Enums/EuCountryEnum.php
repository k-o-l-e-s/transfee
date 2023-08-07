<?php

namespace App\Enums;

enum EuCountryEnum
{
    const EU_COUNTRIES = [
        'AT',
        'BE',
        'BG',
        'CY',
        'CZ',
        'DE',
        'DK',
        'EE',
        'ES',
        'FI',
        'FR',
        'GR',
        'HR',
        'HU',
        'IE',
        'IT',
        'LT',
        'LU',
        'LV',
        'MT',
        'NL',
        'PO',
        'PT',
        'RO',
        'SE',
        'SI',
        'SK',
    ];

    public static function check(String $countryCode): bool
    {
        return in_array(strtoupper($countryCode), self::EU_COUNTRIES);
    }
}

