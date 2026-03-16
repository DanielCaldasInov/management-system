<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ArticleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => 'ART-'.$this->faker->unique()->randomNumber(5, true),
            'name' => $this->faker->words(3, true),
            'description' => $this->faker->paragraph(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'vat_percentage' => $this->faker->randomElement([6.00, 13.00, 23.00]),
            'photo_path' => null,
            'notes' => $this->faker->sentence(),
            'is_active' => $this->faker->boolean(90),
        ];
    }
}
