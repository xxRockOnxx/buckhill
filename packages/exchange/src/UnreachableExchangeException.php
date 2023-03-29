<?php

namespace Lemuel\Exchange;

class UnreachableExchangeException extends \Exception
{
    public function __construct(string $message = 'Unable to reach exchange service')
    {
        parent::__construct($message);
    }
}
