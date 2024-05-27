<?php

namespace Database\Factories;

use App\Models\BankTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BankTransaction>
 */
class BankTransactionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'bank_id' => rand(1, 10),
            'withdraw_deposite_id' => rand(1111111, 9999999999),
            'amount' => rand(100, 1000),
            'type' => [BankTransaction::TYPE_CREADIT, BankTransaction::TYPE_DEBIT][rand(0, 1)],
            'decsription' => $this->faker->sentence(),
            'date' => $this->faker->date,
        ];
    }
}
