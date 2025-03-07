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
        Schema::create('staff', function (Blueprint $table) {
            // $table->id('staff_id');
            // $table->string('first_name');
            // $table->string('last_name');
            // $table->string('email')->unique();
            // $table->string('phone_number')->nullable();
            // $table->enum('role', ['admin', 'waiter', 'chef', 'manager']);
            // $table->decimal('salary', 10, 2)->nullable();
            // $table->string('password_hash');
            // $table->text('work_schedule')->nullable();
            // $table->timestamps();
            $table->bigIncrements('id'); // Primary key for staff
        $table->string('first_name');
        $table->string('last_name');
        $table->string('email')->unique();
        $table->string('phone_number')->nullable();
        $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
