<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\AiService;
use App\Models\ManualMealLog;
use Illuminate\Support\Facades\Log;

use Auth;

class ManualMealLogController extends Controller
{


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

        $manualMeal = ManualMealLog::create([
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


}
