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
        Schema::create('foods', function (Blueprint $table) {
            $table->id();
            $table->string('name');                           // Food name
            $table->string('category')->nullable();           // Protein, carb, fat, fruit, etc.
            $table->integer('calories')->nullable();          // Calories per serving
            $table->float('protein', 8, 2)->nullable();       // g protein
            $table->float('carbs', 8, 2)->nullable();         // g carbs
            $table->float('fat', 8, 2)->nullable();           // g fat
            $table->float('fiber', 8, 2)->nullable();         // g fiber
            $table->float('sugar', 8, 2)->nullable();         // g sugar
            $table->string('serving_size')->nullable();       // e.g. "100g", "1 cup"
            $table->float('unit_weight_g', 8, 2)->nullable(); // Weight in grams for serving
            $table->string('brand')->nullable();              // Optional brand info
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('foods');
    }
};
