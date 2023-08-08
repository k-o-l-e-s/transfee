<?php

namespace App\Services\Bins;

interface BinInterface
{
    public function getCountryCode(int $bin): string;

}
