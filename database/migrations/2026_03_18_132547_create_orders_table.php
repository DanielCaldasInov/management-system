<?php

use App\Models\Entity;
use App\Models\Quote;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();

            $table->enum('type', ['customer', 'supplier'])->default('customer');

            $table->foreignIdFor(Entity::class)->constrained()->restrictOnDelete();
            $table->foreignIdFor(Quote::class)->nullable()->constrained()->nullOnDelete();

            $table->string('reference')->unique();
            $table->date('issue_date');

            $table->enum('status', ['draft', 'closed'])->default('draft');

            $table->decimal('subtotal', 10, 2)->default(0);
            $table->decimal('vat_total', 10, 2)->default(0);
            $table->decimal('total', 10, 2)->default(0);

            $table->text('notes')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
