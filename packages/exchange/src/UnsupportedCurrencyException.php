<?php

namespace Lemuel\Exchange;

use Exception;

class UnsupportedCurrencyException extends Exception
{
    public function __construct(string $currency)
    {
        parent::__construct("Currency {$currency} is not supported");
    }
}
