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
        Schema::create('tours', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('destination');
            $table->string('country');
            $table->decimal('price', 10, 2);
            $table->string('duration');
            $table->decimal('rating', 2, 1);
            $table->string('image')->nullable();
            $table->text('description')->nullable();
            $table->json('itinerary')->nullable();
            $table->json('included')->nullable();
            $table->json('excluded')->nullable();
            $table->enum('category', ['beach', 'adventure', 'cultural', 'luxury', 'safari']);
            $table->timestamps();
            
            // Indexes for common queries
            $table->index('destination');
            $table->index('country');
            $table->index('category');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tours');
    }
};
