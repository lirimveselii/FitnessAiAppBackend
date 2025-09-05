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
        Schema::create('custom_recepies_food', function (Blueprint $table) {
             $table->id();
            $table->foreignId('custom_recepies_id')
                ->constrained('custom_recepies')
                ->onDelete('cascade');
            $table->foreignId('food_id')
                ->constrained('foods')
                ->onDelete('cascade');
            $table->decimal('servings', 10, 4)->default(1);
            $table->string('notes')->nullable();
            $table->timestamps();
            $table->unique(['custom_recepies_id', 'food_id']); // avoid dup rows
                });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_recepies_food');
    }
};
