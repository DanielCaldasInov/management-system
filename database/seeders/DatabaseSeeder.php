<?php

namespace Database\Seeders;

use App\Models\Article;
use App\Models\Company;
use App\Models\Entity;
use App\Models\Invoice;
use App\Models\InvoiceLine;
use App\Models\Order;
use App\Models\OrderLine;
use App\Models\Quote;
use App\Models\QuoteLine;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Storage::disk('public')->deleteDirectory('articles');
        Storage::disk('public')->deleteDirectory('company');
        Storage::disk('public')->makeDirectory('articles');
        Storage::disk('public')->makeDirectory('company');

        User::factory()->create([
            'name' => 'Daniel Silva',
            'email' => 'daniel@example.com',
            'password' => Hash::make('password'),
        ]);

        Company::factory()->create();

        Entity::factory(15)->create(['is_customer' => true, 'is_supplier' => false]);
        Entity::factory(15)->create(['is_customer' => false, 'is_supplier' => true]);

        Article::factory(35)->create();

        Quote::factory(20)->create()->each(function ($quote) {
            $lines = QuoteLine::factory(rand(1, 5))->create([
                'quote_id' => $quote->id,
            ]);

            $quote->update([
                'subtotal' => $lines->sum('subtotal'),
                'vat_total' => $lines->sum('vat_amount'),
                'total' => $lines->sum('total'),
            ]);
        });

        Order::factory(15)->create(['type' => 'customer'])->each(function ($order) {
            $lines = OrderLine::factory(rand(1, 5))->create([
                'order_id' => $order->id,
            ]);

            $subtotal = $lines->sum(fn ($line) => $line->quantity * $line->unit_price);
            $vatTotal = $lines->sum(fn ($line) => ($line->quantity * $line->unit_price) * ($line->vat_percentage / 100));

            $order->update([
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'total' => $subtotal + $vatTotal,
            ]);
        });

        Order::factory(10)->create(['type' => 'supplier'])->each(function ($order) {
            $lines = OrderLine::factory(rand(1, 5))->create([
                'order_id' => $order->id,
            ]);

            $subtotal = $lines->sum(fn ($line) => $line->quantity * $line->unit_price);
            $vatTotal = $lines->sum(fn ($line) => ($line->quantity * $line->unit_price) * ($line->vat_percentage / 100));

            $order->update([
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'total' => $subtotal + $vatTotal,
            ]);
        });

        Invoice::factory(20)->create(['type' => 'supplier'])->each(function ($invoice) {
            $lines = InvoiceLine::factory(rand(1, 5))->create([
                'invoice_id' => $invoice->id,
            ]);

            $subtotal = $lines->sum(fn ($line) => $line->quantity * $line->unit_price);
            $vatTotal = $lines->sum(fn ($line) => ($line->quantity * $line->unit_price) * ($line->vat_percentage / 100));

            $invoice->update([
                'subtotal' => $subtotal,
                'vat_total' => $vatTotal,
                'total' => $subtotal + $vatTotal,
            ]);
        });
    }
}
