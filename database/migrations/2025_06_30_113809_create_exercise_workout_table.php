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
        Schema::create('exercise_workout', function (Blueprint $table) {
            $table->id();
                    $table->foreignId('workout_id')->constrained()->onDelete('cascade');
            $table->foreignId('exercise_id')->constrained()->onDelete('cascade');

            $table->tinyInteger('sets')->nullable();
            $table->tinyInteger('reps')->nullable();
            $table->tinyInteger('rest_seconds')->nullable();
            $table->tinyInteger('duration_seconds')->nullable(); // for cardio/timed exercises
            $table->tinyInteger('order')->nullable(); // position in workout
            $table->text('notes')->nullable(); // optional notes
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('exercise_workout');
    }
};
