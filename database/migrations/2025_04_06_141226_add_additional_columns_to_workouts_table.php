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
        Schema::table('workouts', function (Blueprint $table) {
            
            $table->string('workout_type')->nullable();  // e.g., cardio, strength
            $table->string('workout_day')->nullable();  // e.g., cardio, strength
            $table->decimal('calories_burned', 8, 2)->nullable();  // Estimated calories burned
            $table->string('target_muscle_groups')->nullable();  // e.g., chest, legs, arms
            $table->text('notes')->nullable();  // Optional field for user notes
            $table->enum('status', ['planned', 'in_progress', 'completed'])->default('planned');
            $table->date('workout_date')->nullable();  // Record the date of the workout
            $table->string('difficulty_level')->nullable();  // e.g., beginner, intermediate, advanced
            $table->text('progress_results')->nullable();  // Field for tracking results
            $table->string('tags')->nullable();  // Comma-separated tags for filtering (e.g., "core, HIIT")
            $table->integer('rating')->nullable()->check('rating >= 1 AND rating <= 5');  // Rating (1-5)
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('workouts', function (Blueprint $table) {
            $table->dropColumn([
                'workout_type',
                'calories_burned',
                'target_muscle_groups',
                'notes',
                'status',
                'workout_date',
                'difficulty_level',
                'progress_results',
                'tags',
                'rating',
            ]);
        });
    }
};
