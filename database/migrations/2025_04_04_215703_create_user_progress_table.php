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
        Schema::create('user_progress', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->foreignid('user_id')->constrained('users')->onDelete('cascade');
            $table->decimal('weight_kg', 5, 2)->check('weight_kg > 0');
            $table->decimal('bmi', 5, 2);
            $table->integer('workout_streak')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_progress');
    }
};
