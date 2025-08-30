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
        Schema::create('meals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('diet_day_id')->nullable()->constrained()->onDelete('cascade')->nullable();
            $table->foreignId('created_by_user_id')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('type', ['breakfast', 'lunch', 'dinner', 'snack']);
            $table->string('title');
            $table->text('instructions')->nullable();
            $table->float('calories', 6, 2)->nullable();
            $table->float('protein', 6, 2)->nullable();
            $table->float('carbs', 6, 2)->nullable();
            $table->float('fats', 6, 2)->nullable();
            $table->enum('source', ['ai', 'user', 'custom'])->default('ai');
            $table->boolean('is_custom')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('meals');
    }
};
