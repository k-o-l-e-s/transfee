<?php

namespace App\Services\Bins;

use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class BinlistService implements BinInterface
{
    public function getCountryCode(int $bin): string
    {
        try {
            $cacheKey = "country_code_bin_{$bin}";

            return Cache::remember($cacheKey, now()->addHours(24), function () use ($bin) {
                $url = env('API_BIN_URL') . $bin;
                $response = Http::get($url);
                if ($response->successful()) {
                    $countryCode = data_get($response->json(), 'country.alpha2');
                    return $countryCode ?? '';
                } else {
                    return '';
                }
            });
        } catch (RequestException $exception) {
            return '';
        }
    }

}
