<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Enums\CurrencyEnum; // Assuming CurrencyEnum is in the correct namespace

class TransactionValidationRule implements Rule
{
    public function passes($attribute, $value)
    {
        // Validate bin
        if (!preg_match('/^\d{5,8}$/', $value['bin'])) {
            return false;
        }

        // Validate amount
        if (!is_float($value['amount'])) {
            return false;
        }

        // Validate currency
        if (
            strlen($value['currency']) !== 3 ||
            !CurrencyEnum::check($value['currency'])
        ) {
            return false;
        }

        return true;
    }

    public function message()
    {
        return 'The validation error message.';
    }
}
