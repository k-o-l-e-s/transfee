<?php

namespace Database\Factories;

use App\Enums\CurrencyEnum;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    protected $model = Transaction::class;

    public function definition()
    {
        return [
            'bin' => substr($this->faker->creditCardNumber,8),
            'amount' => $this->faker->randomFloat(0, 10, 1000),
            'currency' => $this->faker->randomElement(CurrencyEnum::values()),
            // ... other attributes
        ];
    }
}
