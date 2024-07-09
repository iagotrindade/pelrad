<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Material>
 */
class MaterialFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        switch (rand(1, 3)) {
            case 1: $status = 'Disponível';
            break;

            case 2: $status = 'Indisponível';
            break;

            case 3: $status = 'Manutenção';
            break;
        }

        return [
            'images' => fake()->imageUrl(),
            'name' => fake()->name(),
            'serial_number' => fake()->name(),
            'description' => fake()->sentence(150),
            'record_number' => fake()->name(),
            'patrimony_number' => fake()->name(),
            'patrimony_value' => fake()->name(),
            'inclusion_document' => fake()->name(),
            'inclusion_date' => fake()->date(),
            'status' => $status
        ];
    }
}
