<?php

namespace Lemuel\Exchange\Tests;

use Illuminate\Support\Facades\Route;
use Lemuel\Exchange\EcbExchangeService;
use Lemuel\Exchange\ExchangeService;
use Lemuel\Exchange\ExchangeServiceProvider;
use Orchestra\Testbench\TestCase;

class ExchangeServiceProviderTest extends TestCase
{
    public function test_default_implementation()
    {
        $this->app->register(ExchangeServiceProvider::class);

        $this->assertInstanceOf(
            EcbExchangeService::class,
            $this->app->make(ExchangeService::class),
        );
    }

    public function test_adds_route_by_default()
    {
        $this->app->register(ExchangeServiceProvider::class);

        $this->assertArrayHasKey('exchange', Route::getRoutes()->get('GET'));
    }

    public function test_does_not_add_route_when_disabled()
    {
        $this->app['config']->set('exchange.routes', false);

        $this->app->register(ExchangeServiceProvider::class);

        $this->assertArrayNotHasKey('exchange', Route::getRoutes()->get('GET'));
    }

    public function test_uses_endpoint_from_config()
    {
        $this->app['config']->set('exchange.endpoint', 'foo');

        $this->app->register(ExchangeServiceProvider::class);

        $this->assertArrayHasKey('foo', Route::getRoutes()->get('GET'));
    }
}
