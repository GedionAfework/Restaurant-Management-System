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
        Schema::create('payments', function (Blueprint $table) {
            $table->id('payment_id');
        $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        $table->decimal('amount_paid', 10, 2);
        $table->enum('payment_method', ['credit_card', 'cash', 'paypal']);
        $table->timestamp('payment_time');
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
