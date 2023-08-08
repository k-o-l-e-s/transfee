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
        $this->apiUrl = env('API_EXCHANGERATES_URL') or die('undefined env: API_EXCHANGERATES_URL');
        $this->apiKey = env('API_EXCHANGERATES_KEY');
    }

    private function fetchExchangeRates()
    {
        //return Cache::remember('exchange_rates1', now()->addMinutes(5), function () {
            $response = Http::withHeaders([
                'apikey' => $this->apiKey,
            ])->get($this->apiUrl . 'exchangerates_data/latest');
            $this->rates = $response->json('rates');

            return $this->rates;
        //});
    }

    public function calculateAmountInEuro(Float $amount, String $currency): Float
    {
        if ($amount == 0) return 0;

        $rates = $this->fetchExchangeRates();
        //var_dump($rates);
        $eurToCurrencyRate = $rates[$currency];

        return sprintf("%01.2f",($amount / $eurToCurrencyRate));
    }
}
