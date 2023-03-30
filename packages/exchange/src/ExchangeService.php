<?php

namespace Lemuel\Exchange;

interface ExchangeService
{
    /**
     * @throws UnsupportedCurrency
     */
    public function getExchangeRate(float $amount, string $currency): float;
}
