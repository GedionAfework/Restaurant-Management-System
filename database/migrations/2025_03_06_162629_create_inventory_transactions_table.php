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
        Schema::create('inventory_transactions', function (Blueprint $table) {
            $table->id('transaction_id');

            // Corrected foreign key reference
            $table->unsignedBigInteger('inventory_id');
            $table->foreign('inventory_id')->references('inventory_id')->on('inventory')->onDelete('cascade');

            $table->enum('transaction_type', ['in', 'out']);
            $table->integer('quantity');
            $table->timestamp('transaction_time');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_transactions');
    }
};
