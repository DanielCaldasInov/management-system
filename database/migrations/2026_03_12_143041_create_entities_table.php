<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entities', function (Blueprint $table) {
            $table->id();

            // Type flags [cite: 47]
            $table->boolean('is_customer')->default(false); // [cite: 48]
            $table->boolean('is_supplier')->default(false); // [cite: 49]

            $table->integer('number')->unique(); // [cite: 51, 52]

            // VAT Number (NIF) kept unencrypted for unique validation [cite: 53, 54]
            $table->string('vat_number')->unique();
            $table->string('name'); // [cite: 55]

            // Encrypted PII Fields
            $table->text('address')->nullable();
            $table->text('zip_code')->nullable(); // [cite: 57]
            $table->text('city')->nullable(); // [cite: 59]
            $table->text('phone')->nullable(); // [cite: 62]
            $table->text('mobile')->nullable(); // [cite: 63]
            $table->text('email')->nullable(); // [cite: 65]
            $table->text('website')->nullable(); // [cite: 64]
            $table->text('notes')->nullable(); // [cite: 69]

            // Relation to Countries [cite: 60, 61]
            $table->unsignedBigInteger('country_id')->nullable();

            $table->boolean('gdpr_consent')->default(false); // [cite: 66, 67, 68]
            $table->boolean('is_active')->default(true); // [cite: 70, 71, 72]

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entities');
    }
};
