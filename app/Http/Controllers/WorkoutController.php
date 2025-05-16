<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Services\AIService;
use App\Events\WorkoutEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use App\Http\Controllers\ExerciseController;


class WorkoutController extends Controller
{
    protected $aiService;
    // protected $exerciseController;
    public $user;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService ;
        $this->user = Auth::user();

        // $this->$exerciseController = $exerciseController;
    }

    public function getAllUserWorkouts(Request $request){

        // return Workout::where('user_id',$request->user()->id);
        $workouts = Workout::with('exercises')
        ->where('user_id', 1)
        ->get();
        // dd($workouts);


         return  response()->json( [$workouts], 200);

    }

    public function generateWorkoutPlan(Request $request)
    {
        // dd("test");
        try {
            // Step 1: Get input data
            $data = $request->only([
                'user_type',
                'fitness_goal',
                'workout_days_per_week',
                'fitness_level',
                'focus_area',
                'available_equipment',
                'workout_type_preference',
                'target_weight_goal',
                'injuries_or_limitations',
                'workout_time_per_session',
                'structured_or_flexible_plan',
                'include_nutrition_plan',
            ]);
    
            // Step 2: Generate AI-based workout data
            $aiService = new AIService();
            $workoutDataList = $aiService->getWorkoutPlan(...array_values($data));
    
            if (!is_array($workoutDataList) || empty($workoutDataList)) {
                Log::warning('Invalid response from AIService', ['response' => $workoutDataList]);
                return response()->json(['error' => 'Invalid workout plan from AI'], 422);
            }
            // dd($workoutDataList);
    
            $savedWorkouts = [];
    
            // Step 3: Loop and store each workout
            foreach ($workoutDataList as $index => $item) {
                try {
                    $workout = $this->storeWorkout($item['workout'], $item['exercises']);
                    event(new WorkoutEvent($workout));
                    $savedWorkouts[] = $workout;
                } catch (\Throwable $e) {
                    Log::error("Error storing workout #$index", [
                        'error' => $e->getMessage(),
                        'workout_data' => $item['workout'],
                    ]);
                    // Continue saving others; optionally collect errors too
                }
            }
    
            return response()->json([
                'message' => 'Workout plan generated and stored successfully',
                'workouts' => $savedWorkouts,
            ], 201);
    
        } catch (\Throwable $e) {
            Log::error('AI generation or storage failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            return response()->json([
                'error' => 'Failed to generate workout plan.',
                'details' => $e->getMessage(),
            ], 500);
        }
    }
    
    public function storeWorkout(array $data, array $exerciseData): Workout
    {
        // dd($data['workout_day']);
        try {
            $workout = new Workout([
                'user_id' => 1,//$this->user->id
                'title' => $data['title'],
                'description' => $data['description'],
                'workout_day' => $data['workout_day'],
                'duration_min' => $data['duration_min'],
                'intensity_level' => $data['intensity_level'],
                'workout_type' => $data['workout_type'],
                'calories_burned' => $data['calories_burned'],
                'target_muscle_groups' => $data['target_muscle_groups'],
                'notes' => $data['notes'] ?? null,
                'status' => $data['status'],
                'workout_date' => $data['workout_date'],
                'difficulty_level' => $data['difficulty_level'],
                'progress_results' => $data['progress_results'] ?? null,
                'tags' => $data['tags'],
                'rating' => $data['rating'],
            ]);
    
            $workout->save();
    
            // Log workout success
            Log::info('Workout saved', ['workout_id' => $workout->id]);
    
            // Store exercises
            $exerciseController = app()->make(ExerciseController::class);
            $exerciseController->storeExercise($exerciseData, $workout->id);
    
            // Load with exercises
            return Workout::with('exercises')->find($workout->id);
    
        } catch (\Throwable $e) {
            Log::error('Workout creation failed', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
    
            throw $e; // Let generateWorkoutPlan handle it
        }
    }
    

    public function testWebSocets(){

        event(new MessageSent('Hello from Laravel!'));
    }


}
