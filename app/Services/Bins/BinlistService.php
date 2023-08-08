<?php

namespace App\Services\Bins;

use Illuminate\Support\Facades\Http;

class BinlistService implements BinInterface
{
    public function getCountryCode(int $bin): string
    {
        $url = env('API_BIN_URL') . $bin;
        $response = Http::get($url);
        if ($response->successful()) {
            return data_get($response->json(), 'country.alpha2');
        }

        return '';
    }

}
