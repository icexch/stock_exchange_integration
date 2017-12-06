<?php

namespace Warchiefs\StockExchangeIntegration;

use Illuminate\Support\ServiceProvider;
use Warchiefs\StockExchangeIntegration\StockExchangeRegistry;

class StockExchangeIntegrationProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
	    $this->publishes([
		    __DIR__.'/Config/exchange.php' => config_path('exchange.php'),
	    ]);

        foreach (array_keys(config('exchange.available')) as $exchange) {
            $exchangeClassName = ucfirst($exchange);
            $exchange_path = __NAMESPACE__ . '\\Containers\\' . $exchangeClassName;

            if (class_exists($exchange_path)) {
                $this->app->make(StockExchangeRegistry::class)
                    ->register($exchange, new $exchange_path);
            }
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(StockExchangeRegistry::class);
    }
}
