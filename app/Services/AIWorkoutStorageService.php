<?php

namespace App\Services;

use App\Models\Workout;
use App\Models\Exercise;
use App\Models\ExerciseAlias;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;


 class AIWorkoutStorageService {


public function store(array $aiData)
{
    $allNormalizedNames = [];

        try {
        foreach ($aiData as $data) {
            foreach ($data['exercises'] as $exData) {
                $allNormalizedNames[] = $exData['normalized_name'];
            }
        }

        $allNormalizedNames = array_unique($allNormalizedNames);

        $exercises = Exercise::whereIn('normalized_name', $allNormalizedNames)->get();
        $aliases = ExerciseAlias::whereIn('alias', $allNormalizedNames)->get();

        $exerciseMap = $exercises->keyBy('normalized_name');
        $aliasMap = $aliases->keyBy('alias');

        $notFound = [];

        foreach ($aiData as $data) {
            try {
                $workoutData = $data['workout'];
                $exercises = $data['exercises'];

                $workout = Workout::create([
                    'user_id' => 1,
                    'title' => $workoutData['title'],
                    'description' => $workoutData['description'],
                    'workout_day' => $workoutData['workout_day'],
                    'duration_min' => $workoutData['duration_min'],
                    'intensity_level' => $workoutData['intensity_level'],
                    'workout_type' => $workoutData['workout_type'],
                    'calories_burned' => $workoutData['calories_burned'],
                    'target_muscle_groups' => $workoutData['target_muscle_groups'],
                    'notes' => $workoutData['notes'],
                    'status' => $workoutData['status'],
                    'workout_date' => $workoutData['workout_date'],
                    'difficulty_level' => $workoutData['difficulty_level'],
                    'progress_results' => $workoutData['progress_results'],
                    'tags' => $workoutData['tags'],
                    'rating' => $workoutData['rating'],
                ]);

                foreach ($exercises as $index => $exData) {
                    try {
                        $normalized = $exData['normalized_name'];
                        $exercise = $exerciseMap[$normalized] ?? null;
                        $alias = $aliasMap[$normalized] ?? null;

                        if ($exercise) {
                            $workout->exercises()->attach($exercise->id, [
                                'sets' => $exData['sets'],
                                'reps' => $exData['reps'],
                                'rest_seconds' => $exData['rest_seconds'],
                                'order' => $index + 1,
                            ]);
                        } elseif ($alias) {
                            $workout->exercises()->attach($alias->exercise_id, [
                                'sets' => $exData['sets'],
                                'reps' => $exData['reps'],
                                'rest_seconds' => $exData['rest_seconds'],
                                'order' => $index + 1,
                            ]);
                        } else {
                            $alias = ExerciseAlias::create([]);

                            $notFound[] = $normalized;
                        }
                    } catch (\Exception $e) {
                        Log::error("Error attaching exercise to workout: " . $e->getMessage());
                    }
                }

            } catch (\Exception $e) {
                Log::error("Error creating workout or processing exercises: " . $e->getMessage());
            }
        }

        if (!empty($notFound)) {
            Log::warning('Exercises not found: ' . implode(', ', array_unique($notFound)));
        }

    } catch (\Exception $e) {
        Log::critical("Failed to store AI-generated workout data: " . $e->getMessage());
    }

}


 }
?>