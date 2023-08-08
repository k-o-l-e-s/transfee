<?php

namespace App\Http\Requests;

use App\Models\Transaction;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\CurrencyEnum;

class TransactionRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'bin' => ['required', 'digits_between:5,8'],
            'amount' => ['required', 'numeric'],
            'currency' => [
                'required',
                'size:3',
                function ($attribute, $value, $fail) {
                    if (!CurrencyEnum::check($value)) {
                        $fail("The $attribute is invalid.");
                    }
                },
            ],
        ];
    }

    public function store(TransactionRequest $request)
    {
        // The request has already been validated at this point
        $transaction = Transaction::create($request->all());
    }

}
