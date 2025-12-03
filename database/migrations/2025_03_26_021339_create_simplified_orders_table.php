<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Drop existing tables if they exist
        // Schema::dropIfExists('order_items');
        // Schema::dropIfExists('orders');

        // Create simplified orders table
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->nullable()->constrained('customers')->onDelete('set null'); // Links to customers table
            $table->foreignId('table_id')->nullable()->constrained('tables', 'table_id')->onDelete('set null'); // Links to tables
            $table->foreignId('food_id')->constrained('food')->onDelete('cascade'); // Links to food table
            $table->integer('quantity')->default(1);
            $table->decimal('price', 10, 2); // Price of the item at order time
            $table->decimal('total_amount', 10, 2); // quantity * price
            $table->string('status')->default('pending'); // pending, preparing, ready, completed, cancelled
            $table->text('order_notes')->nullable(); // Customer notes/instructions
            $table->text('kitchen_notes')->nullable(); // Kitchen staff notes
            $table->timestamp('preparing_at')->nullable(); // When kitchen started preparing
            $table->timestamp('ready_at')->nullable(); // When order is ready
            $table->timestamp('completed_at')->nullable(); // When order is completed
            $table->timestamp('estimated_completion_at')->nullable(); // Estimated completion time
            $table->integer('priority')->default(0); // Order priority (higher = more urgent)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};