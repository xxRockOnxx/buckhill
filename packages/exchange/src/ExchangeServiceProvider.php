<?php

namespace Lemuel\Exchange;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class ExchangeServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(ExchangeService::class, EcbExchangeService::class);

        if (config('exchange.routes', true)) {
            $this->registerRoutes();
        }
    }

    protected function registerRoutes()
    {
        Route::get(config('exchange.endpoint', 'exchange'), ExchangeController::class);
    }
}
