<?php

namespace Lemuel\Exchange\Tests;

use Illuminate\Support\Facades\Http;
use Lemuel\Exchange\EcbExchangeService;
use Lemuel\Exchange\UnreachableExchangeException;
use Lemuel\Exchange\UnsupportedCurrencyException;
use Orchestra\Testbench\TestCase;

class EcbExchangeServiceTest extends TestCase
{
    public function test_successful_exchange_rate()
    {
        $responseXML = file_get_contents(__DIR__ . '/stubs/EcbResponse.xml');

        $http = Http::fake([
            'mocked-exchange.com' => Http::response($responseXML, 200),
        ]);

        $service = new EcbExchangeService($http);
        $service->setEndpoint('mocked-exchange.com');

        // Test positive amount
        $this->assertEquals(1.0841, $service->getExchangeRate(1, 'USD'));
        $this->assertEquals(1.4803, $service->getExchangeRate(1, 'CAD'));
        $this->assertEquals(19.7029, $service->getExchangeRate(1, 'ZAR'));

        // Test negative amount
        $this->assertEquals(1.0841 * -10, $service->getExchangeRate(-10, 'USD'));

        // Test lowercase currency
        $this->assertEquals(1.0841, $service->getExchangeRate(1, 'usd'));
    }

    public function test_unreachable_endpoint()
    {
        $http = Http::fake([
            'mocked-exchange.com' => Http::response('', 500),
        ]);

        $service = new EcbExchangeService($http);
        $service->setEndpoint('mocked-exchange.com');

        $this->expectException(UnreachableExchangeException::class);

        $service->getExchangeRate(1, 'USD');
    }

    public function test_with_invalid_xml()
    {
        $http = Http::fake([
            'mocked-exchange.com' => Http::response('invalid xml', 200),
        ]);

        $service = new EcbExchangeService($http);
        $service->setEndpoint('mocked-exchange.com');

        $this->expectException(UnreachableExchangeException::class);

        $service->getExchangeRate(1, 'USD');
    }

    public function test_unsupported_currency()
    {
        $responseXML = file_get_contents(__DIR__ . '/stubs/EcbResponse.xml');

        $http = Http::fake([
            'mocked-exchange.com' => Http::response($responseXML, 200),
        ]);

        $service = new EcbExchangeService($http);
        $service->setEndpoint('mocked-exchange.com');

        $this->expectException(UnsupportedCurrencyException::class);

        $service->getExchangeRate(1, 'ASD');
    }
}
