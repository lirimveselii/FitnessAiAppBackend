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
        Schema::create('user_preferences', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->foreignid('user_id')->constrained('users')->onDelete('cascade');
            $table->text('dietary_restrictions')->nullable();
            $table->text('preferred_cuisine')->nullable();
            $table->enum('workout_type', ['strength', 'cardio', 'yoga', 'mixed'])->default('mixed');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_preferences');
    }
};
