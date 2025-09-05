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
        Schema::create('custom_recepies', function (Blueprint $table) {
       $table->id();
        $table->foreignId('user_id')->constrained()->onDelete('cascade');

        $table->string('name');
        $table->string('slug')->nullable()->index();      // for nice URLs/unique per user
        $table->text('description')->nullable();          // short summary

        // Portions & timing
        $table->unsignedSmallInteger('servings')->default(1);     // how many portions it makes
        $table->unsignedSmallInteger('prep_minutes')->default(0);
        $table->unsignedSmallInteger('cook_minutes')->default(0);
        $table->boolean('is_public')->default(false);     // shareable/visible
        $table->softDeletes();                            // allows restore
        $table->decimal('cached_calories', 10, 2)->nullable();
        $table->decimal('cached_protein_g', 10, 2)->nullable();
        $table->decimal('cached_carbs_g', 10, 2)->nullable();
        $table->decimal('cached_fat_g', 10, 2)->nullable();

        $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('custom_food_library');
    }
};
