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
        Schema::create('manual_meal_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('date');
            $table->enum('meal_type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->string('title');
            $table->float('calories', 6, 2);
            $table->float('protein', 6, 2)->nullable();
            $table->float('carbs', 6, 2)->nullable();
            $table->float('fats', 6, 2)->nullable();
            $table->enum('source', ['user', 'custom_food_library', 'scanned'])->default('user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manual_meal_logs');
    }
};
