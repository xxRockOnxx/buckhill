<?php

namespace Lemuel\Exchange;

interface ExchangeService
{
    /**
     * @throws UnsupportedCurrencyException
     */
    public function getExchangeRate(float $amount, string $currency): float;
}
