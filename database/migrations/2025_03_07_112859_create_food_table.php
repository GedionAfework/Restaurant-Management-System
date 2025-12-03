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
        Schema::create('food', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->nullable()->constrained('menu_categories', 'category_id')->onDelete('set null');
            $table->string('name');
            $table->string('type')->nullable(); // e.g., "Appetizer", "Main Course"
            $table->json('availability_times')->nullable(); // breakfast, lunch, dinner
            $table->json('tags')->nullable(); // spicy, vegetarian, vegan, etc.
            $table->integer('calories')->nullable();
            $table->integer('protein')->nullable(); // in grams
            $table->integer('carbs')->nullable(); // in grams
            $table->integer('fat')->nullable(); // in grams
            $table->json('allergens')->nullable();
            $table->text('description')->nullable();
            $table->string('picture')->nullable(); // Path to uploaded image
            $table->decimal('price', 8, 2);
            $table->boolean('is_available')->default(true);
            $table->boolean('is_featured')->default(false);
            $table->integer('display_order')->default(0);
            $table->integer('preparation_time')->nullable(); // in minutes
            $table->timestamps();
        });
    }
    
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
