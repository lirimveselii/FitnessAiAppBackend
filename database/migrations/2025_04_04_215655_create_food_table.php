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
            $table->id('id')->primary();
            $table->foreignid('meal_plan_id')->constrained('meal_plans')->onDelete('cascade');
            $table->string('name');
            $table->string('serving_size');
            $table->integer('calories')->check('calories > 0');
            $table->decimal('protein_g', 5, 2);
            $table->decimal('carbs_g', 5, 2);
            $table->decimal('fats_g', 5, 2);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food');
    }
};
