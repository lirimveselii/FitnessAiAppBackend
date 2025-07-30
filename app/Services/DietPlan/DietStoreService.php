<?php 
namespace App\Services\DietPlan;
use App\Services\AiClient;
use App\Services\AiService;
use App\models\DietDay;
use App\models\DietPlan;
use App\models\Meal;
use App\models\MealIngredient;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;





class DietStoreService {

public function store(array $dietPlna, string $goal): JsonResponse
{
    $userId = Auth::id() ?? 1;
    $startDate = Carbon::tomorrow();
    $days = $dietPlna['diet_plan'] ?? [];

    if (empty($days)) {
        return response()->json([
            'message' => 'No diet plan data provided.',
        ], 400);
    }

    try {
        DB::beginTransaction();

        // Create Diet Plan
        $dietPlan = DietPlan::create([
            'user_id' => $userId,
            'goal' => $goal,
            'start_date' => $startDate->toDateString(),
            'duration_days' => count($days),
            'version' => 1,
            'is_active' => true,
            'is_manual' => false,
            'source' => 'ai',
        ]);

        foreach ($days as $index => $dayData) {

            dd($dayData);
            // Validate day data
            if (!isset($dayData['meals']) || !is_array($dayData['meals'])) {
                DB::rollBack();
                return response()->json([
                    'message' => "Invalid or missing meals for day {$index}.",
                ], 422);
            }

            $dietDay = DietDay::create([
                'diet_plan_id' => $dietPlan->id,
                'day_number' => $index + 1,
                'date' => $startDate->copy()->addDays($index)->toDateString(),
            ]);

            if (!$dietDay) {
                DB::rollBack();
                Log::error("Failed to create DietPlanDay for day {$index}");
                return response()->json([
                    'message' => "Failed to create diet day {$index}.",
                ], 500);
            }

            foreach ($dayData['meals'] as $mealIndex => $mealData) {
                $meal = Meal::create([
                    'diet_day_id' => $dietDay->id,
                    'created_by_user_id' => $userId,
                    'type' => $mealData['meal_type'] ?? 'meal',
                    'title' => $mealData['title'] ?? 'Meal',
                    'instructions' => $mealData['description'] ?? '',
                    'calories' => $mealData['calories'] ?? 0,
                    'protein' => $mealData['protein'] ?? 0,
                    'carbs' => $mealData['carbs'] ?? 0,
                    'fats' => $mealData['fat'] ?? 0,
                    'source' => 'ai',
                    'is_custom' => false,
                ]);


                if (!$meal) {
                    DB::rollBack();
                    Log::error("Failed to create Meal for day {$index}, meal {$mealIndex}");
                    return response()->json([
                        'message' => "Failed to create meal for day {$index}, meal {$mealIndex}.",
                    ], 500);
                }

                foreach($mealData["ingredients"] as $ingredients ){


                    $mealIngredients = MealIngredient::create([
                        "meal_id" => $meal->id ,
                        "name"=>$ingredients['name'],
                        "quantity"=>$ingredients['amount'],
                        // "unit"=>$ingredients[],
                        // "calories"=>$ingredients[],


                    ]);
                }
            }
        }

        DB::commit();

        return response()->json([
            'message' => 'Diet plan created successfully.',
            'diet_plan_id' => $dietPlan->id,
        ], 201);

    } catch (\Throwable $e) {
        DB::rollBack();

        Log::error('Failed to create diet plan', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ]);

        return response()->json([
            'message' => 'An unexpected error occurred while creating the diet plan.',
            'error' => $e->getMessage(),
        ], 500);
    }
}
}

?>