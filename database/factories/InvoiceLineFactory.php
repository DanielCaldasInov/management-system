<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Article;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceLineFactory extends Factory
{
    public function definition(): array
    {
        $quantity = $this->faker->randomFloat(2, 1, 50);
        $unitPrice = $this->faker->randomFloat(2, 5, 500);

        return [
            'invoice_id' => Invoice::factory(),

            'article_id' => Article::inRandomOrder()->first()->id ?? null,

            'description' => $this->faker->words(4, true),
            'quantity' => $quantity,
            'unit_price' => $unitPrice,
            'vat_percentage' => 23.00,
            'total' => $quantity * $unitPrice,
        ];
    }
}
