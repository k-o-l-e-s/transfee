<?php

namespace App\Providers;

use App\Services\Bins\BinInterface;
use App\Services\Exchanges\ApilayerExchangeService;
use App\Services\Exchanges\ExchangeInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(BinInterface::class, function ($app) {
            $binProviderName = Config::get('bin.default');

            if ($binProviderName === 'Binlist') {
                return new BinlistProvider();
            }

            throw new \InvalidArgumentException("Unsupported bin provider: {$binProviderName}");
        });

        $this->app->bind(ExchangeInterface::class, function ($app) {
            $exchangeProviderName = Config::get('exchange.default');

            if ($exchangeProviderName === 'Apilayer') {
                return new ApilayerExchangeService(Config::get('exchange.data_providers.Apilayer'));
            }

            throw new \InvalidArgumentException("Unsupported exchange provider: {$exchangeProviderName}");
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
