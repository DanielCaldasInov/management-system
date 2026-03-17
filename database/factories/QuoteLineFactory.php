<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\Quote;
use Illuminate\Database\Eloquent\Factories\Factory;

class QuoteLineFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 10);
        $unitPrice = $this->faker->randomFloat(2, 10, 500);
        $vatPercentage = $this->faker->randomElement([6, 13, 23]);

        $subtotal = $quantity * $unitPrice;
        $vatAmount = $subtotal * ($vatPercentage / 100);
        $total = $subtotal + $vatAmount;

        return [
            'quote_id' => Quote::factory(),
            'article_id' => Article::inRandomOrder()->first()->id ?? Article::factory(),
            'description' => $this->faker->words(3, true),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'vat_percentage' => $vatPercentage,
            'subtotal' => $subtotal,
            'vat_amount' => $vatAmount,
            'total' => $total,
        ];
    }
}
