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
        Schema::create('menu_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_id')->constrained('food')->onDelete('cascade');
            $table->string('name'); // e.g., "Small", "Medium", "Large", "Regular", "Double"
            $table->decimal('price_modifier', 10, 2)->default(0); // Price difference from base price
            $table->string('size')->nullable(); // Optional size indicator
            $table->boolean('is_default')->default(false); // Default variant
            $table->integer('display_order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_variants');
    }
};
