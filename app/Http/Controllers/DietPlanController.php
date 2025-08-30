<?php

namespace App\Http\Controllers;
use App\Services\AIService;
use App\Services\DietPlan\DietStoreService;
use App\Services\DietPlan\AiDietStoreService;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DietPlanController extends Controller
{
 

     
     protected AIService $aiService;

    public function __construct(AIService $aiService){

        $this->aiService = $aiService;
    }


   public function generateDiet(Request $request)
{
      $user =  User::find(1);//  auth()->user(); after you put in the rout group 
     $data = [
        'weight' => $user->weight_kg,
        'height' => $user->height_cm,
        'age' => $user->age,
        'gender' => $user->gender,
        'activity_level' => $request->input('activity_level'),
        'goal' => $request->input('diet_goal'),
        'goal_speed' => $request->input('goal_speed'),
        'diet_style' => $request->input('diet_style'),
        'dietary_restrictions' => $request->input('dietary_restrictions', []),
        'meals_per_day' => $request->input('meals_per_day'),
        'budget_level' => $request->input('budget_level'),
        'fasting_enabled' => $request->input('fasting_enabled', false),
        'goal_reason' => $request->input('goal_reason'),
        'goal_description'=> $request->input('goal_description'),
        'food_you_dont_want'=> $request->input('food_you_dont_want')

    ];

    // First AI call
    // $macros = $this->aiService->generateMacrosWithAi($data); 
    // dd($macros);
    // $input = array_merge($macros, $data);
    // sleep(100); 
    $dietPlan = $this->aiService->generateDietWithAi($data);
    dd($dietPlan);
    $storeDiet = app(DietStoreService::class)->store($dietPlan , $data['goal']);

    return response()->json($dietPlan); 
}

    public function search()
      {
        $response = Http::get('https://api.nal.usda.gov/fdc/v1/foods/search', [
            'query' => 'apple', // use a specific word for guaranteed result
            'pageSize' => 10,
            'api_key' => env('USDA_API_KEY'),
        ]);

        if (!$response->successful()) {
            return response()->json(['error' => 'API call failed'], 500);
        }

        $foods = $response->json()['foods'] ?? [];

        if (empty($foods)) {
            return response()->json(['message' => 'No food found'], 404);
        }

        return response()->json(collect($foods)->pluck('description'));
    }
}


