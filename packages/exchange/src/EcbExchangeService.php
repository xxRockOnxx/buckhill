<?php

namespace Lemuel\Exchange;

use Illuminate\Http\Client\Factory;

class EcbExchangeService implements ExchangeService
{
    private string $endpoint = 'https://www.ecb.europa.eu/stats/eurofxref/eurofxref-daily.xml';

    private $http;

    public function __construct(Factory $http)
    {
        $this->http = $http;
    }

    public function getEndpoint(): string
    {
        return $this->endpoint;
    }

    public function setEndpoint(string $endpoint): void
    {
        $this->endpoint = $endpoint;
    }

    public function getExchangeRate(float $amount, string $currency): float
    {
        $xml = $this->http->get($this->endpoint);

        if ($xml->failed()) {
            throw new UnreachableExchangeException();
        }

        libxml_use_internal_errors(true);

        $obj = simplexml_load_string($xml->body());

        if (! $obj) {
            throw new UnreachableExchangeException();
        }

        $array = json_decode(json_encode($obj->Cube->Cube), true)['Cube'];
        $collection = collect($array)->pluck('@attributes');

        $found = $collection->firstWhere('currency', strtoupper($currency));

        if (! $found) {
            throw new UnsupportedCurrencyException($currency);
        }

        return $amount * $found['rate'];
    }
}
