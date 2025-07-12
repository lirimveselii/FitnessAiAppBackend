<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Services\AIService;
use App\Services\AIWorkoutStorageService;
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
                $workouts = Workout::with(['exercises' => function ($query) {
                $query->orderBy('pivot_order'); // or manually later
            }])
            ->where('user_id', 1) // or ->where('user_id', 1)
            ->get();
        // dd($workouts);


         return  response()->json( [$workouts], 200);

    }

    public function generateWorkoutPlan(Request $request)
    {
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
    
            $aiService = new AIService();
            $workoutDataList = $aiService->getWorkoutPlan($data);

            $workout = app(AIWorkoutStorageService::class)->store($workoutDataList); // ... auth()->user() when i put the root to the authed groop i will add the 
    
            return response()->json([
                'message' => 'Workout plan generated and stored successfully'
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
    
    public function storeCustomWorkout(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'description' => 'nullable|string',
        'workout_day' => 'nullable|string|max:255',
        'duration_min' => 'nullable|integer|min:1',
        'exercises' => 'required|array|min:1',
        'exercises.*.id' => 'required|exists:exercises,id',
        'exercises.*.sets' => 'nullable|integer|min:1',
        'exercises.*.reps' => 'nullable|integer|min:1',
        'exercises.*.order' => 'nullable|integer|min:1',
    ]);

    try {
        $workout = Workout::create([
            'user_id' => auth()->id() ?? 1, // Replace `1` with actual auth when ready
            'title' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'workout_day' => $validated['workout_day'] ?? null,
            'duration_min' => $validated['duration_min'] ?? null,
        ]);

        $exerciseData = collect($validated['exercises'])->mapWithKeys(function ($exercise) {
            return [
                $exercise['id'] => [
                    'sets' => $exercise['sets'] ?? null,
                    'reps' => $exercise['reps'] ?? null,
                    'order' => $exercise['order'] ?? null,
                ]
            ];
        })->toArray();

        // dd($exerciseData);
        $workout->exercises()->attach($exerciseData);

        return response()->json([
            'message' => 'Workout created successfully.',
            'workout' => $workout->load('exercises'),
        ]);

    } catch (\Throwable $e) {
        Log::error('Workout creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'request_data' => $request->all(),
        ]);

        return response()->json([
            'message' => 'Something went wrong while creating the workout.',
            'error' => $e->getMessage(),
        ], 500);
    }
}


public function updateWorkout(Request $request, Workout $workout)
{
    try {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'workout_day' => 'nullable|string|max:255',
            'duration_min' => 'nullable|integer|min:1',
            'exercises' => 'required|array|min:1',
            'exercises.*.id' => 'required|exists:exercises,id',
            'exercises.*.sets' => 'nullable|integer|min:1',
            'exercises.*.reps' => 'nullable|integer|min:1',
            'exercises.*.order' => 'nullable|integer|min:1',
        ]);

        $workout->update([
            'title' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'workout_day' => $validated['workout_day'] ?? null,
            'duration_min' => $validated['duration_min'] ?? null,
        ]);

        $exerciseData = collect($validated['exercises'])->mapWithKeys(function ($exercise) {
            return [
                $exercise['id'] => [
                    'sets' => $exercise['sets'] ?? null,
                    'reps' => $exercise['reps'] ?? null,
                    'order' => $exercise['order'] ?? null,
                ]
            ];
        })->toArray();

        $workout->exercises()->sync($exerciseData);

        return response()->json([
            'message' => 'Workout updated successfully.',
            'workout' => $workout->load('exercises'),
        ]);

    } catch (\Throwable $e) {
        Log::error('Workout update failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'workout_id' => $workout->id,
            'request_data' => $request->all(),
        ]);

        return response()->json([
            'message' => 'Something went wrong while updating the workout.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
public function deleteWorkout(Workout $workout)
{
    try {
        $workout->exercises()->detach();

        $workout->delete();

        return response()->json([
            'message' => 'Workout deleted successfully.'
        ]);

    } catch (\Throwable $e) {
        Log::error('Workout deletion failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
            'workout_id' => $workout->id,
        ]);

        return response()->json([
            'message' => 'Something went wrong while deleting the workout.',
            'error' => $e->getMessage(),
        ], 500);
    }
}

    

}
