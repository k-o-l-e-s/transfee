<?php
// app/Services/ExchangeRateService.php
namespace App\Services\Exchanges;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class ApilayerExchangeService implements ExchangeInterface
{
    protected $apiUrl;
    protected $apiKey;
    protected $rates;

    public function __construct()
    {
        $this->apiUrl = config('exchange.data_providers.'.config('exchange.default').'.root_url');
        $this->apiKey = config('exchange.data_providers.'.config('exchange.default').'.auth_key');
    }

    private function fetchExchangeRates()
    {
        try {
            return Cache::remember('exchange_rates', now()->addMinutes(5), function () {
                $response = Http::withHeaders([
                    'apikey' => $this->apiKey,
                ])->get($this->apiUrl . 'exchangerates_data/latest');

                if (!$response->successful()) {
                    Log::error('Failed to fetch exchange rates from the API.', [
                        'status' => $response->status(),
                        'response' => $response->json(),
                    ]);
                } else {
                    $this->rates = $response->json('rates');
                    if (empty($this->rates)) {
                        Log::error('Exchange rates data is missing.', [
                            'status' => $response->status(),
                            'response' => $response->json(),
                        ]);
                    } else {
                        return $this->rates;
                    }
                }
                return [];
            });
        } catch (RequestException $exception) {
            return [];
        }
    }

    public function calculateAmountInEuro(Float $amount, String $currency): Float
    {
        if ($amount == 0) return 0;

        $rates = $this->fetchExchangeRates();

        if (!isset($rates[$currency])) {
            Log::error('Exchange rate for currency ['.$currency.'] is not available.');
            throw new \Exception("Exchange rate for currency '{$currency}' is not available.");
        }

        $eurToCurrencyRate = $rates[$currency];

        return sprintf("%01.2f",($amount / $eurToCurrencyRate));
    }
}
