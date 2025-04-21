<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Services\AIService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ExerciseController;
use Illuminate\Support\Facades\Log;


class WorkoutController extends Controller
{
    protected $aiService;
    protected $exerciseController;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService ;

        // $this->$exerciseController = $exerciseController;
    }

    public function getAllUserWorkouts(Request $request){

        // return Workout::where('user_id',$request->user()->id);
        $workouts = Workout::with('exercises')
        ->where('user_id', 2)
        ->get();


         return  response()->json( ["user_workout" => $workouts ], 200);

    }

    public function generateWorkoutPlan(Request $request)
    {
        $prount = $request->prount;
    
        try {
            $workoutData = $this->aiService->getWorkoutPlan($prount);
    
            // Validate workout data structure
            if (!isset($workoutData['workout_number'])) {
                Log::warning('Missing workout_number key from AI response', ['response' => $workoutData]);
                return response()->json(['error' => 'Invalid workout structure from AI'], 422);
            }
    
            // Single workout
            if (!$workoutData['workout_number']) {
                try {
                    $this->storeWorkout($workoutData['workout'], $workoutData['exercises']);
                    Log::info('Single workout stored successfully');
                } catch (\Throwable $e) {
                    Log::error('Failed to store single workout', [
                        'error' => $e->getMessage(),
                        'data' => $workoutData['workout']
                    ]);
                    return response()->json(['error' => 'Failed to save workout.'], 500);
                }
            }
    
            // Multiple workouts
            else {
                if (!isset($workoutData['workouts']) || !is_array($workoutData['workouts'])) {
                    Log::warning('Invalid or missing workouts array in AI response', ['response' => $workoutData]);
                    return response()->json(['error' => 'Invalid workouts array.'], 422);
                }
    
                foreach ($workoutData['workouts'] as $index => $item) {
                    try {
                        $this->storeWorkout($item['workout'], $item['exercises']);
                        Log::info("Workout #$index stored successfully");
                    } catch (\Throwable $e) {
                        Log::error("Error storing workout #$index", [
                            'error' => $e->getMessage(),
                            'workout_data' => $item['workout']
                        ]);
                        // Optionally continue or break depending on the need
                        // return response()->json(['error' => "Failed to save workout #$index"], 500);
                    }
                }
            }
    
            return response()->json(['message' => 'Workout plan generated and stored successfully'], 201);
    
        } catch (\Throwable $e) {
            Log::error('AI service failed or unexpected error occurred', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Failed to generate workout plan.'], 500);
        }
    }
    public function storeWorkout($data, $exerciseData)
    {
        // dd($data);
    
        try {
            $workout = new Workout();
            $workout->user_id = 2; // Replace with auth()->id() when ready
            $workout->title = $data["title"];
            $workout->description = $data["description"];
            $workout->workout_day = $data["week_day"];
            $workout->duration_min = $data["duration_min"];
            $workout->intensity_level = $data["intensity_level"];
            $workout->workout_type = $data["workout_type"];
            $workout->calories_burned = $data["calories_burned"];
            $workout->target_muscle_groups = $data["target_muscle_groups"];
            $workout->notes = $data["notes"] ?? null;
            $workout->status = $data["status"];
            $workout->workout_date = $data["workout_date"];
            $workout->difficulty_level = $data["difficulty_level"];
            $workout->progress_results = $data["progress_results"] ?? null;
            $workout->tags = $data["tags"];
            $workout->rating = $data["rating"];
    
            $workout->save();
    
            // Log workout success
            Log::info('Workout saved', ['workout_id' => $workout->id]);
    
            // Use container to resolve ExerciseController
            $exerciseController = app()->make(ExerciseController::class);
    
            // Store Exercises
            $exerciseController->storeExercise($exerciseData, $workout->id);
    
            // Return success
            return response()->json([
                'message' => 'Workout and exercises created successfully',
                'workout_id' => $workout->id
            ], 201);
    
        } catch (\Throwable $e) {
            // Log the error with stack trace
            Log::error('Workout creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
    
            return response()->json([
                'error' => 'Something went wrong while saving the workout.',
                'details' => $e->getMessage()
            ], 500);
        }
    }


}
