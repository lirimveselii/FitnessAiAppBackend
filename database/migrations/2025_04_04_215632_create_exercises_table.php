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
        Schema::create('exercises', function (Blueprint $table) {
            $table->id('id')->primary();
            $table->foreignid('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->string('name');
            $table->integer('sets')->check('sets > 0');
            $table->integer('reps')->check('reps > 0');
            $table->integer('rest_seconds')->default(30);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercises');
    }
};
