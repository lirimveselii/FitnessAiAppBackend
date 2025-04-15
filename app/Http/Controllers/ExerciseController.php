<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Exercise;
use Illuminate\Support\Facades\Log;

class ExerciseController extends Controller
{

    public function getExercisesForWorkout($workoutId){


        dd($workoutId);



    }
    

   public function storeExercise($exercisesData, $workoutId)
   {
    if (!is_array($exercisesData)) {
        Log::warning('Invalid exercise data format', ['data' => $exercisesData]);
        return;
    }

    foreach ($exercisesData as $index => $item) {
        try {
            $exercise = new Exercise();

            $exercise->workout_id = $workoutId;
            $exercise->name = $item['exercise_name'] ?? 'Unnamed Exercise';
            $exercise->category = $item['category'] ?? 'General';
            $exercise->muscle_group = $item['muscle_group'] ?? 'Unknown';
            $exercise->equipment = $item['equipment'] ?? 'pending';
            $exercise->description = $item['description'] ?? null;
            $exercise->video_url = $item['video_url'] ?? null;
            $exercise->difficulty_level = $item['difficulty_level'] ?? null;
            $exercise->calories_burned = $item['calories_burned'] ?? null;
            $exercise->duration_seconds = $item['duration_seconds'] ?? null;
            $exercise->intensity = $item['intensity'] ?? null;
            $exercise->tags = $item['tags'] ?? null;
            $exercise->sets = $item['sets'] ?? null;
            $exercise->reps = $item['reps'] ?? null;
            $exercise->rest_seconds = $item['rest_time'] ?? null;

            $exercise->save();

            Log::info("Exercise #$index saved successfully", [
                'workout_id' => $workoutId,
                'exercise_name' => $exercise->name
            ]);
        } catch (\Throwable $e) {
            Log::error("Failed to store exercise #$index", [
                'error' => $e->getMessage(),
                'exercise_data' => $item,
                'workout_id' => $workoutId
            ]);
            // Optionally continue with next or stop completely
            // continue;
            break;
        }
    }
}
}
