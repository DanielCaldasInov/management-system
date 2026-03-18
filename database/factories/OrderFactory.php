<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderFactory extends Factory
{
    public function definition(): array
    {
        $year = date('Y');

        return [
            'type' => 'customer',
            'entity_id' => Entity::inRandomOrder()->first()->id ?? Entity::factory(),
            'quote_id' => null,
            'reference' => 'ENC-'.$year.'-'.str_pad($this->faker->unique()->numberBetween(1, 9999), 4, '0', STR_PAD_LEFT),
            'issue_date' => $this->faker->date(),
            'status' => $this->faker->randomElement(['draft', 'closed']),
            'subtotal' => 0,
            'vat_total' => 0,
            'total' => 0,
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
