<?php 

namespace App\Services;
use App\Services\AiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Utils\AiResponseParser;



class AIService
{

    // public function getWorkoutPlan()
    // {
    //   dd("tesssss");
    //     $rawRespounse =   $this->queryDeepSeek();

    //     return  $rawRespounse;

    //     // $apiKey = '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'; 
        
    //     // $response = Http::withHeaders([
    //     //     'Authorization' => 'Bearer ' . $apiKey,
    //     //     'Content-Type'  => 'application/json',
    //     //     ])->post('https://api-inference.huggingface.co/v1/chat/completions', [
    //     //         'provider' => 'novita',
    //     //         'model' => 'Qwen/QwQ-32B',
    //     //         'messages' => [
    //     //             [
    //     //                 'role' => 'user',
    //     //                 'content' => $prount ,
    //     //             ],
    //     //         ],
    //     //         'max_tokens' => 500,
    //     //     ]);
            
    //     //     dd($response);
    //     //     $data = $response->json();
    
    //     // if (isset($data['choices'][0]['message']['content'])) {
    
    //     //    $rawRespounse =   $this->queryDeepSeek($data['choices'][0]['message']['content']);
    
    //     //    $jsonRespounse = $this->trimRespounse($rawRespounse);
    //     // //    dd($jsonRespounse);

    //     //     return  $jsonRespounse;
         
    //     // } else {
    //     //     return response()->json([
    //     //         'error' => 'No answer found.',
    //     //         'raw' => $data
    //     //     ], 500);
    //     // }
    // }



    public function askHuggingFace()
    {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('TOGETEHR_API_KEY'),
            'Content-Type' => 'application/json',
        ])->post('https://api.together.xyz/v1/chat/completions', [
            'model' => 'meta-llama/Llama-3.3-70B-Instruct-Turbo',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => 'What is the capital of France?'
                ]
            ]
        ]);
    
        if ($response->successful()) {
            return $response->json()['choices'][0]['message'];
        } else {
            return response()->json([
                'error' => 'API call failed',
                'status' => $response->status(),
                'body' => $response->body()
            ]);
        }
    }
    


    
    // public function getWorkoutPlan($userType,$fitnessGoal,$workoutDaysPerWeek,$fitnessLevel,$focusArea,$availableEquipment,$workoutTypePreference,$targetWeightGoal,$injuriesOrLimitations,$workoutTimePerSession,$structuredOrFlexiblePlan,$includeNutritionPlan)
   public function getWorkoutPlan(array $inputs): array
    {
        $prompt =  $this->buildWorkoutPrompt($inputs);
        $rawResponse = app(AiClient::class)->send($prompt);
        // dd($rawResponse);
    //   dd("resrt");
      return AiResponseParser::clean($rawResponse);
    //   dd($finalres );


        $focusAreaString = is_array($focusArea) ? implode(', ', $focusArea) : $focusArea;
        // $availableEquipmentString = is_array($availableEquipment) ? implode(', ', $availableEquipment) : $availableEquipment;
        $injuriesOrLimitationsString = is_array($injuriesOrLimitations) ? implode(', ', $injuriesOrLimitations) : $injuriesOrLimitations;


      
        $apiKey = env('TOGETEHR_API_KEY'); 
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->timeout(200)
        ->post('https://api.together.xyz/v1/chat/completions', [
            'model' => 'meta-llama/Llama-4-Maverick-17B-128E-Instruct-FP8',
            'messages' => [
                            [
                                "role" => "user",
                                "content" => "Create a personalized fitness workout plan based on the following user input:\\n\\n\
{\\n\
  \\\"user_type\\\": \\\"$userType\\\",\\n\
  \\\"fitness_goal\\\": \\\"$fitnessGoal\\\",\\n\
  \\\"workout_days_per_week\\\": $workoutDaysPerWeek,\\n\
  \\\"fitness_level\\\": \\\"$fitnessLevel\\\",\\n\
  \\\"focus_area\\\": \\\"$focusAreaString\\\",\\n\
  \\\"workout_type_preference\\\": \\\"$workoutTypePreference\\\",\\n\
  \\\"target_weight_goal\\\": $targetWeightGoal,\\n\
  \\\"injuries_or_limitations\\\": \\\"$injuriesOrLimitationsString\\\",\\n\
  \\\"workout_time_per_session\\\": $workoutTimePerSession,\\n\
  \\\"structured_or_flexible_plan\\\": \\\"$structuredOrFlexiblePlan\\\",\\n\
  \\\"include_nutrition_plan\\\": \\\"$includeNutritionPlan\\\"\\n\
}\\n\\n\
Requirements:\\n\\n\
1. The output must be valid JSON with **no extra comments or explanations** — only clean, parsable JSON.\\n\\n\
2. The plan should include either:\\n\
   - A **single workout object** if the user wants 1 workout session per week, or\\n\
   - An **array of workout objects**, one for each workout day, if the user wants multiple days per week.\\n\\n\
3. **Muscle Group Splitting** (VERY IMPORTANT for multi-day plans):\\n\
   - For multiple days, split muscle groups scientifically, e.g.:\\n\
     - Day 1: Upper Body Push (Chest, Shoulders, Triceps)\\n\
     - Day 2: Lower Body (Quads, Hamstrings, Glutes, Calves)\\n\
     - Day 3: Upper Body Pull (Back, Biceps)\\n\
     - Or use a proven full-body split spread over the week — DO NOT repeat the same full-body workout every day.\\n\\n\
4. Each workout MUST include the field \\\"workout_day\\\" with a valid weekday name (\\\"Monday\\\", \\\"Tuesday\\\", etc.).\\n\\n\
5. Include **as many exercises per workout based on the user goals**:\\n\
   - For **beginner**: simple exercises, bodyweight/light weights, fewer sets/reps.\\n\
   - For **intermediate**: moderate intensity, weights, more sets/reps.\\n\
   - For **advanced**: complex/high-intensity exercises, more volume.\\n\\n\
6. All fields listed below are MANDATORY and must be filled for both workouts and exercises.\\n\\n\
7. Use these exact field names and structure for each workout and exercises:\\n\\n\
**Workout object fields:**\\n\
- title (string)\\n\
- description (string)\\n\
- workout_day (string, mandatory weekday)\\n\
- duration_min (integer)\\n\
- intensity_level (string: \\\"low\\\", \\\"moderate\\\", or \\\"high\\\")\\n\
- workout_type (string)\\n\
- calories_burned (number)\\n\
- target_muscle_groups (string)\\n\
- notes (string)\\n\
- status (string: \\\"planned\\\", \\\"in_progress\\\", or \\\"completed\\\")\\n\
- workout_date (string, format \\\"YYYY-MM-DD\\\")\\n\
- difficulty_level (string: \\\"beginner\\\", \\\"intermediate\\\", or \\\"advanced\\\")\\n\
- progress_results (string)\\n\
- tags (string)\\n\
- rating (integer)\\n\\n\
**Exercise object fields:**\\n\
- exercise_name (string)\\n\
- sets (integer)\\n\
- reps (integer)\\n\
- rest_time (integer, seconds)\\n\
- category (string)\\n\
- muscle_group (string)\\n\
- description (string)\\n\
- video_url (string)\\n\
- difficulty_level (string)\\n\
- calories_burned (number)\\n\
- duration_seconds (integer)\\n\
- intensity (string: \\\"low\\\", \\\"moderate\\\", or \\\"high\\\")\\n\\n\
---\\n\\n\
### Example Output Formats:\\n\\n\
#### Single workout (1 day):\\n\
{\\n\
  \\\"workout\\\": { ... },\\n\
  \\\"exercises\\\": [ ... ]\\n\
}\\n\\n\
#### Multiple workouts (multi-day):\\n\
[\\n\
  {\\n\
    \\\"workout\\\": { ... },\\n\
    \\\"exercises\\\": [ ... ]\\n\
  },\\n\
  {\\n\
    \\\"workout\\\": { ... },\\n\
    \\\"exercises\\\": [ ... ]\\n\
  },\\n\
  ...\\n\
]\\n\\n\
---\\n\\n\
Generate the workout plan strictly following all the above rules."


                            ]
                        ]
        ]);
    
        $data = $response->json();
        
        //  dd( $data);
        $rawJsonRespounse;
        if (isset($response['choices'][0]['message']['content'])) {
          $rawJsonRespounse = $response['choices'][0]['message']['content'];
      } else {
          Log::error('Invalid AI response structure', ['response' => $response]);
          throw new \Exception('AI service failed to return a valid response.');
      }
      
       
        $res = $this->trimRespounse($rawJsonRespounse);
    
        if (isset($rawJsonRespounse)) {
    
            return $res;
    
        } else {
            return response()->json([
                'error' => 'No answer returned.',
                'details' => $data
            ], 5000);
        }
    }
    
    
    public function trimRespounse($rawRespounse){
    
 // Remove surrounding whitespace
 $cleaned = trim($rawRespounse);

 // Remove triple quotes and backticks
 $cleaned = str_replace(['"""', '```json', '```'], '', $cleaned);

 // Remove leading/trailing whitespace again
 $cleaned = trim($cleaned);
 // Try to decode the JSON
 $aiData = json_decode($cleaned, true);

 // Debug the decoded result


 
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        dd('JSON decode error:', json_last_error_msg(), $cleaned);
    }
    

    return  $aiData;
    
    }


    public function testGoogleAi(){


$response = Http::withHeaders([
    'Content-Type' => 'application/json',
])->post('https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent?key=AIzaSyBAsr4w5H5RhyEIwDmPbeiKuV7ep6D28S4', [
    'contents' => [
        [
            'parts' => [
                [
                    'text' => 'what is the capital of albania'
                ]
            ]
        ]
    ]
]);

$data = $response->json();

dd($data); // dumps the response

    }
    public function buildWorkoutPrompt(array $params): string
{
    $template = File::get(resource_path('/prompts/workout_plan.txs'));

    $replacements = [
        '{{user_type}}' => $params['user_type'],
        '{{fitness_goal}}' => $params['fitness_goal'],
        '{{workout_days_per_week}}' => $params['workout_days_per_week'],
        '{{fitness_level}}' => $params['fitness_level'],
        '{{focus_area}}' => is_array($params['focus_area']) ? implode(', ', $params['focus_area']) : $params['focus_area'],
        '{{workout_type_preference}}' => $params['workout_type_preference'],
        '{{target_weight_goal}}' => $params['target_weight_goal'],
        '{{injuries_or_limitations}}' => is_array($params['injuries_or_limitations']) ? implode(', ', $params['injuries_or_limitations']) : $params['injuries_or_limitations'],
        '{{workout_time_per_session}}' => $params['workout_time_per_session'],
        '{{structured_or_flexible_plan}}' => $params['structured_or_flexible_plan'],
        '{{include_nutrition_plan}}' => $params['include_nutrition_plan'],
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $template);
}
    

}

