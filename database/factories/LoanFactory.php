<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Loan>
 */
class LoanFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        switch (rand(1, 2)) {
            case 1: $status = 'Aberta';
            break;

            case 2: $status = 'Fechada';
            break;
        }

        return [
            'from' => fake()->name(),
            'to' => fake()->name(),
            'graduation' => fake()->name(),
            'name' => fake()->name(),
            'idt' => fake()->name(),
            'materials_info' => fake()->name(),
            'contact' => fake()->phone(),
            'return_date' => fake()->date(),
            'file' => fake()->name(),
            'status' => $status
        ];
    }
}
