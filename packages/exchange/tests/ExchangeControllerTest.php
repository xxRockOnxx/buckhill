<?php

namespace Lemuel\Exchange\Tests;

use Illuminate\Http\Request;
use Lemuel\Exchange\ExchangeController;
use Lemuel\Exchange\ExchangeService;
use Lemuel\Exchange\UnsupportedCurrency;
use Orchestra\Testbench\TestCase;

class ExchangeControllerTest extends TestCase
{
    public function test_successful_response()
    {
        $service = $this->mock(ExchangeService::class, function ($mock) {
            $mock->shouldReceive('getExchangeRate')
                ->once()
                ->with(100, 'USD')
                ->andReturn(1000);
        });

        $request = Request::create('/exchange', 'GET', [
            'amount' => 100,
            'currency' => 'USD',
        ]);

        $response = (new ExchangeController())($request, $service);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertSuccessResponse($response->getData(true), [
            'amount' => 1000,
        ]);
    }

    public function test_validation_error()
    {
        $service = $this->mock(ExchangeService::class, function ($mock) {
            $mock->shouldNotReceive('getExchangeRate');
        });

        $request = Request::create('/exchange', 'GET', [
            'amount' => 'invalid',
            'currency' => '',
        ]);

        $response = (new ExchangeController())($request, $service)->getData(true);

        $this->assertEquals(0, $response['success']);
        $this->assertEquals('Unprocessable Entity', $response['error']);
        $this->assertArrayHasKey('amount', $response['errors']);
        $this->assertArrayHasKey('currency', $response['errors']);
    }

    public function test_unsupported_currency()
    {
        $error = new UnsupportedCurrency('ABC');

        $service = $this->mock(ExchangeService::class, function ($mock) use ($error) {
            $mock->shouldReceive('getExchangeRate')
                ->once()
                ->with(100, 'ABC')
                ->andThrow($error);
        });

        $request = Request::create('/exchange', 'GET', [
            'amount' => 100,
            'currency' => 'ABC',
        ]);

        $response = (new ExchangeController())($request, $service);

        $this->assertEquals(400, $response->getStatusCode());
        $this->assertErrorResponse($response->getData(true), $error->getMessage());
    }

    public function test_other_errors()
    {
        $error = new \Exception('Something went wrong');

        $service = $this->mock(ExchangeService::class, function ($mock) use ($error) {
            $mock->shouldReceive('getExchangeRate')
                ->once()
                ->with(100, 'ABC')
                ->andThrow($error);
        });

        $request = Request::create('/exchange', 'GET', [
            'amount' => 100,
            'currency' => 'ABC',
        ]);

        $response = (new ExchangeController())($request, $service);

        $this->assertEquals(500, $response->getStatusCode());
        $this->assertErrorResponse($response->getData(true), 'Internal Server Error');
    }

    public function assertSuccessResponse($response, $data = [], $extra = [])
    {
        $this->assertEquals($response, [
            'success' => 1,
            'data' => $data,
            'error' => null,
            'errors' => [],
            'extra' => $extra,
        ]);
    }

    public function assertErrorResponse($response, $error = 'Internal Server Error', $errors = [], $trace = [])
    {
        $this->assertEquals($response, [
            'success' => 0,
            'data' => [],
            'error' => $error,
            'errors' => $errors,
            'trace' => $trace,
        ]);
    }
}
