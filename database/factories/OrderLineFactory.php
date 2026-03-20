<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Entity;
use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

class OrderLineFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 10);
        $unitPrice = $this->faker->randomFloat(2, 10, 500);
        $costPrice = $unitPrice * 0.6;
        $vatPercentage = $this->faker->randomElement([6, 13, 23]);

        $subtotal = $quantity * $unitPrice;
        $vatAmount = $subtotal * ($vatPercentage / 100);
        $total = $subtotal + $vatAmount;

        return [
            'order_id' => Order::factory(),
            'article_id' => Article::inRandomOrder()->first()->id ?? Article::factory(),
            'supplier_id' => Entity::where('is_supplier', true)->inRandomOrder()->first()->id ?? null,
            'description' => $this->faker->words(3, true),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'cost_price' => $costPrice,
            'vat_percentage' => $vatPercentage,
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total' => $total,
        ];
    }
}
