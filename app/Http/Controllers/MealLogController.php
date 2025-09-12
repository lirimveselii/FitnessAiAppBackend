<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Models\MealLog;
use Illuminate\Support\Facades\Log;
use App\Services\LogsServices\MealLogService;

use Auth;

class MealLogController extends Controller
{
     public $mealLogService;

    public function __construct(){
        $this->mealLogService = app(MealLogService::class);
    }

public function logMeal(Request $request)
{
    try {
        $validated = $request->validate([
            'meal_type' => 'required|in:breakfast,lunch,dinner,snack',
            'title'     => 'nullable|string|max:255',
            'calories'  => 'required|numeric|min:0',
            'protein'   => 'nullable|numeric|min:0',
            'carbs'     => 'nullable|numeric|min:0',
            'fats'      => 'nullable|numeric|min:0',
            'source'    => 'required|in:user,custom_food_library,scanned,ai'
        ]);

        // create the meal log using the service
        $mealLog = $this->mealLogService->createMealLog($validated);

        if (!$mealLog) {
            return response()->json([
                'message' => 'Failed to create meal log'
            ], 500);
        }

        return response()->json([
            'message' => 'Meal log created successfully',
            'data'    => $mealLog,
        ], 201);

    } catch (\Exception $e) {
        \Log::error('Meal log creation failed: '.$e->getMessage(), [
            'trace' => $e->getTraceAsString(),
            'input' => $request->all(),
        ]);

        return response()->json([
            'message' => 'An error occurred while creating the meal log',
            'error'   => $e->getMessage()
        ], 500);
    }
}


   public function estimatedCaloriesCount(Request $request)
{
    try {
        $validated = $request->validate([
            'description' => 'required|array',
            'source' => 'nullable|string|max:255',
            'meal_type' => 'nullable|string|max:255',
        ]);

        $aiService = app(AiService::class);
        $estimateMacros = $aiService->estimateMealMacro($validated['description']);
        // dd($estimateMacros);
        // You should remove the meal log from here and use the method that is on the service
        $manualMeal = MealLog::create([
            'user_id'   => Auth::id() ?? 1,
            'source'    => $validated['source'] ?? 'manual',
            'meal_type' => $validated['meal_type'] ?? 'unspecified',
            'log_date'  => now()->toDateString(),
            'date'      => now()->toDateString(),
            'calories'  => $estimateMacros['calories'],
            'carbs'     => $estimateMacros['carbs_g'],
            'fats'      => $estimateMacros['fats_g'],
            'protein'   => $estimateMacros['protein_g'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Meal log created successfully.',
            'data'    => $manualMeal
        ], 201);

    } catch (\Exception $e) {
        Log::error('Error estimating calories: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while estimating the meal calories.',
            'error'   => $e->getMessage()
        ], 500);
    }
}

public function dailyCaloriesSummary(Request $request)
{
    
    try {
        $userId = Auth::id() ?? 1;
        $date = $request->query('date', now()->toDateString());

        $summary = MealLog::where('user_id', $userId)
            ->whereDate('log_date', $date)
            ->selectRaw('SUM(calories) as total_calories, SUM(protein) as total_protein, SUM(carbs) as total_carbs, SUM(fats) as total_fats')
            ->first();

        return response()->json([
            'success' => true,
            'data'    => $summary
        ], 200);

    } catch (\Exception $e) {
        Log::error('Error fetching daily calories summary: ' . $e->getMessage());

        return response()->json([
            'success' => false,
            'message' => 'An error occurred while fetching the daily calories summary.',
            'error'   => $e->getMessage()
        ], 500);
    }


}
}
