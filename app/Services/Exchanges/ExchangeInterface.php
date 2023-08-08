<?php

namespace App\Services\Exchanges;

interface ExchangeInterface
{
    public function calculateAmountInEuro(Float $amount, String $currency): Float;

}
