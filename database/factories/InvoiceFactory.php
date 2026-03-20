<?php

namespace Database\Factories;

use App\Models\Entity;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        $subtotal = $this->faker->randomFloat(2, 50, 5000);
        $vat = $subtotal * 0.23;

        return [
            'entity_id' => Entity::where('is_supplier', true)->inRandomOrder()->first()->id ?? Entity::factory()->create(['is_customer' => false, 'is_supplier' => true])->id,
            'type' => 'supplier',
            'reference' => 'FT-SUP-'.$this->faker->unique()->numberBetween(1000, 9999),
            'issue_date' => $this->faker->dateTimeBetween('-6 months', 'now'),
            'due_date' => $this->faker->dateTimeBetween('now', '+30 days'),
            'status' => $this->faker->randomElement(['pending', 'paid']),
            'subtotal' => $subtotal,
            'vat_total' => $vat,
            'total' => $subtotal + $vat,
            'notes' => $this->faker->optional()->sentence(),
        ];
    }
}
