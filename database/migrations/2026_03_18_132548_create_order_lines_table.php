<?php

use App\Models\Article;
use App\Models\Order;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_lines', callback: function (Blueprint $table) {
            $table->id();

            $table->foreignIdFor(Order::class)->constrained()->cascadeOnDelete();
            $table->foreignIdFor(Article::class)->nullable()->constrained()->nullOnDelete();

            $table->foreignId('supplier_id')->nullable()->constrained('entities')->nullOnDelete();

            $table->text('description');
            $table->decimal('quantity', 8, 2)->default(1);

            $table->decimal('unit_price', 10, 2);
            $table->decimal('cost_price', 10, 2)->nullable();
            $table->decimal('vat_percentage', 5, 2);

            $table->decimal('subtotal', 10, 2);
            $table->decimal('vat_amount', 10, 2);
            $table->decimal('total', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_lines');
    }
};
