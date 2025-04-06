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
        Schema::create('workout_exercise', function (Blueprint $table) {
            $table->id();
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->integer('order')->default(0);  // The order in which the exercise appears in the workout
            $table->integer('sets')->check('sets > 0');  // Number of sets for this exercise in the workout
            $table->integer('reps')->check('reps > 0');  // Number of reps per set for this exercise
            $table->integer('rest_time')->default(30);  // Rest time between sets (in seconds)
            $table->text('notes')->nullable();  // Any notes specific to this exercise in the workout
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('workout_exercise');
    }
};
