<?php

namespace Lemuel\Exchange;

use Exception;

class UnsupportedCurrency extends Exception
{
    public function __construct(string $currency)
    {
        parent::__construct("Currency {$currency} is not supported");
    }
}
