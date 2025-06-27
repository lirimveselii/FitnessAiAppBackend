<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\MealPlan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Throwable;
use App\Services\AIService;


class MealPlanController extends Controller
{
   
     protected AIService $aiService;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService;
    }


    public function generateDiet( Request $request){

        $data = $request->all();
        $plan = $this->aiService->getDietPlan($data);
        $this->storeDietPlan($plan);

    }


public function storeDietPlan(array $plan): void
{
    foreach ($plan as $dayPlan) {
        $day = $dayPlan['day'] ?? null;

        if (!$day || !isset($dayPlan['meals']) || !is_array($dayPlan['meals'])) {
            Log::warning('Invalid day plan structure', ['dayPlan' => $dayPlan]);
            continue; // Skip malformed entry
        }

        foreach ($dayPlan['meals'] as $index => $meal) {
            try {
                // Validate required fields
                if (
                    !isset($meal['meal_name'], $meal['prep_instructions'], $meal['calories'], $meal['macros'], $meal['type']) ||
                    !isset($meal['macros']['protein'], $meal['macros']['carbs'], $meal['macros']['fat'])
                ) {
                    Log::warning('Skipping invalid meal entry', ['meal' => $meal]);
                    continue;
                }

                MealPlan::create([
                    'user_id'     => Auth::id() ?? 1,
                    'title'       => $meal['meal_name'],
                    'description' => $meal['prep_instructions'],
                    'calories'    => (int) $meal['calories'],
                    'protein_g'   => (float) $meal['macros']['protein'],
                    'carbs_g'     => (float) $meal['macros']['carbs'],
                    'fats_g'      => (float) $meal['macros']['fat'],
                    'day_meal'    => $day,
                    'meal_type'   => $meal['type'],
                    'ingredients' => json_encode($meal['ingredients'] ?? []),
                ]);
            } catch (Throwable $e) {
                Log::error('Failed to store meal plan', [
                    'day' => $day,
                    'meal_index' => $index,
                    'meal' => $meal,
                    'error' => $e->getMessage(),
                ]);
               
                continue;
            }
        }
    }
}

public function getFullDiet() {


$mondayMeals = MealPlan::where('user_id', 1)->get();

}


public function getTodaysMeals(){

      $today = now()->format('l');

$mondayMeals = MealPlan::where('user_id', 1)
    ->where('day_meal', $today)
    ->get();

dd($mondayMeals);   

}
}
