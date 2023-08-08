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

    public function __construct($config = [])
    {
        $this->apiUrl = env('API_EXCHANGERATES_URL') or die('Undefined env variable: API_EXCHANGERATES_URL');
        $this->apiKey = env('API_EXCHANGERATES_KEY') or die('Undefined env variable: API_EXCHANGERATES_KEY');
    }

    private function fetchExchangeRates()
    {
        try {
            return Cache::remember('exchange_rates', now()->addMinutes(5), function () {
                $response = Http::withHeaders([
                    'apikey' => $this->apiKey,
                ])->get($this->apiUrl . 'exchangerates_data/latest');

                if (!$response->successful()) {
                    throw new \Exception('Failed to fetch exchange rates from the API.');
                }

                $this->rates = $response->json('rates');

                if (empty($this->rates)) {
                    throw new \Exception('Exchange rates data is missing.');
                }

                return $this->rates;
            });
        } catch (RequestException $exception) {
            throw new \Exception('Exchange rates API connection error.');
        }
    }

    public function calculateAmountInEuro(Float $amount, String $currency): Float
    {
        if ($amount == 0) return 0;

        $rates = $this->fetchExchangeRates();

        if (!isset($rates[$currency])) {
            throw new \Exception("Exchange rate for currency '{$currency}' is not available.");
        }

        $eurToCurrencyRate = $rates[$currency];

        return sprintf("%01.2f",($amount / $eurToCurrencyRate));
    }
}
