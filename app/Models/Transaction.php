<?php

namespace App\Models;

use App\Enums\CurrencyEnum;
use App\Enums\EuCountryEnum;
use App\Rules\TransactionValidationRule;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Validator;


class Transaction extends Model
{
    use HasFactory;
    protected $guarded = [];

    public static $rules = [
        'validation' => [TransactionValidationRule::class],
    ];

    protected $fillable = ['bin', 'amount', 'currency', 'bin_country', 'eu', 'amount_eur', 'fee'];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $validator = Validator::make($attributes, [
            'bin' => ['required', 'digits_between:5,8'],
            'amount' => ['required', 'numeric'],
            'currency' => ['required', 'size:3', function ($attribute, $value, $fail) {
                if (!CurrencyEnum::check($value)) {
                    $fail("The $attribute is invalid.");
                }
            }],
        ]);

        if ($validator->fails()) {
            throw new \Illuminate\Validation\ValidationException($validator);
        }
    }

    public function isEu()
    {
        if (!$this->bin_country) {
            $this->getCountryCode();
        }
        $this->eu = EuCountryEnum::check($this->bin_country);

        return $this->eu;
    }

}
