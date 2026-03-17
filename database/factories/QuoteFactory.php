<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteFactory extends Factory
{
    public function definition(): array
    {
        $issueDate = $this->faker->dateTimeBetween('-1 year', 'now');
        $validUntil = (clone $issueDate)->modify('+30 days');

        return [
            'entity_id' => Entity::inRandomOrder()->first()->id ?? Entity::factory(),
            'reference' => 'PROP-'.$this->faker->unique()->numberBetween(10000, 99999),
            'issue_date' => $issueDate->format('Y-m-d'),
            'valid_until' => $validUntil->format('Y-m-d'),
            'status' => $this->faker->randomElement(['draft', 'sent', 'accepted', 'rejected']),
            'subtotal' => 0,
            'vat_total' => 0,
            'total' => 0,
            'notes' => $this->faker->optional()->paragraph(),
        ];
    }
}
