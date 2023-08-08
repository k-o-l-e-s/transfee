<?php

namespace App\Models;

use App\Enums\CurrencyEnum;
use App\Enums\EuCountryEnum;
use App\Rules\TransactionValidationRule;
use App\Services\Bins\BinlistService;
use App\Services\Exchanges\ApilayerExchangeService;
use App\Traits\ClearableAttributes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;
use App\Http\Requests\TransactionRequest;

class Transaction extends Model
{
    use HasFactory, ClearableAttributes;
    protected $guarded = [];

    public static $rules = [
        'validation' => [TransactionValidationRule::class],
    ];

    protected $fillable = ['bin', 'amount', 'currency', 'bin_country', 'eu', 'amount_eur', 'fee'];

    public function isEu()
    {
        if (!$this->bin_country) {
            $this->getCountryByBin();
        }
        $this->eu = EuCountryEnum::check($this->bin_country);

        return $this->eu;
    }

    public function getCountryByBin()
    {
        $binService = app(BinlistService::class);
        return $this->bin_country = $binService->getCountryCode($this->bin);
    }

    public function calculateFee()
    {

        $exchangeRateService = app(ApilayerExchangeService::class);
        $this->amountEur = $exchangeRateService->calculateAmountInEuro($this->amount, $this->currency);

        $this->feeRate = $this->isEu() ? 0.01 : 0.02;
        $this->fee = $this->amountEur * $this->feeRate;
        $this->fee = sprintf("%01.2f",$this->fee);

        return $this->fee;
    }

}
