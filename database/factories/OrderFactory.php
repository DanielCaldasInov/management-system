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
            'entity_id' => function (array $attributes) {
                $isSupplier = isset($attributes['type']) && $attributes['type'] === 'supplier';

                return Entity::where($isSupplier ? 'is_supplier' : 'is_customer', true)
                    ->inRandomOrder()->first()->id
                    ?? Entity::factory()->create([
                        'is_customer' => ! $isSupplier,
                        'is_supplier' => $isSupplier,
                    ])->id;
            },
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
