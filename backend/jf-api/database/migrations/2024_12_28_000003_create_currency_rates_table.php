<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('currency_rates', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // e.g., 'USD', 'EUR', 'GBP'
            $table->string('name'); // e.g., 'US Dollar', 'Euro'
            $table->decimal('rate', 10, 4); // Base rate (1 USD = X)
            $table->decimal('buy_rate', 10, 4); // Bureau buy rate
            $table->decimal('sell_rate', 10, 4); // Bureau sell rate
            $table->string('flag')->nullable(); // Emoji flag for UI
            $table->timestamps();
            
            // Indexes for quick lookup
            $table->index('code');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('currency_rates');
    }
};
