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
        Schema::create('exercise_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('workout_id')->constrained('workouts')->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained('exercises')->onDelete('cascade');
            $table->integer('sets')->check('sets > 0');  // Number of sets performed
            $table->integer('reps')->check('reps > 0');  // Number of reps per set
            $table->decimal('weight', 8, 2)->nullable();  // Weight lifted during the exercise (if applicable)
            $table->integer('duration')->nullable();  // Duration of exercise (e.g., in seconds for cardio)
            $table->text('notes')->nullable();  // Optional user notes (e.g., how the exercise felt)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_logs');
    }
};
