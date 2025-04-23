<?php 

namespace App\Services;
use Illuminate\Support\Facades\Http;


class AIService
{

    public function getWorkoutPlan($prount)
    {
        $rawRespounse =   $this->queryDeepSeek($prount);

        return  $rawRespounse;

        // $apiKey = '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'; 
        
        // $response = Http::withHeaders([
        //     'Authorization' => 'Bearer ' . $apiKey,
        //     'Content-Type'  => 'application/json',
        //     ])->post('https://api-inference.huggingface.co/v1/chat/completions', [
        //         'provider' => 'novita',
        //         'model' => 'Qwen/QwQ-32B',
        //         'messages' => [
        //             [
        //                 'role' => 'user',
        //                 'content' => $prount ,
        //             ],
        //         ],
        //         'max_tokens' => 500,
        //     ]);
            
        //     dd($response);
        //     $data = $response->json();
    
        // if (isset($data['choices'][0]['message']['content'])) {
    
        //    $rawRespounse =   $this->queryDeepSeek($data['choices'][0]['message']['content']);
    
        //    $jsonRespounse = $this->trimRespounse($rawRespounse);
        // //    dd($jsonRespounse);

        //     return  $jsonRespounse;
         
        // } else {
        //     return response()->json([
        //         'error' => 'No answer found.',
        //         'raw' => $data
        //     ], 500);
        // }
    }
    
    public function queryDeepSeek($prount)
    {
        $apiKey = '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'; 
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->timeout(100)
        ->post('https://router.huggingface.co/together/v1/chat/completions', [
            'model' => 'deepseek-ai/DeepSeek-V3',
            'max_tokens' => 10000,
            'messages' => [
                            [
                                "role" => "user",
                                "content" => "Create a personalized fitness workout plan based on the following user input: $prount.

- If the user wants a single workout session, return **just one workout object** with its exercises.
- If the user wants a plan for multiple days (e.g., a week), return an **array of workout plans**, one per day.
- **The field \"workout_number\" is mandatory**:
    - If the number of workouts is **one**, the value should be \"workout_number\": false.
    - If the number of workouts is **greater than one**, the value should be \"workout_number\": true.
- Every workout **must include** the \"week_day\" field to specify on which day it is planned (e.g., \"Monday\", \"Tuesday\", etc.).
- The structure and field names must always be exactly the same, with all fields filled.
- All fields in both the \"workout\" and \"exercises\" sections are mandatory.
- The output must be **valid JSON** with no additional characters, comments, explanations, or markdown formatting — only clean, parsable JSON compatible with json_decode.

---

### JSON FORMAT:

#### For a single workout:
\"workout_number\": ..., 
{
  \"workout\": {
    \"title\": \"...\",
    \"description\": \"...\",
    \"week_day\": \"Monday\",
    \"duration_min\": ...,
    \"intensity_level\": \"low | moderate | high\",
    \"workout_type\": \"...\",
    \"calories_burned\": ...,
    \"target_muscle_groups\": \"...\",
    \"notes\": \"...\",
    \"status\": \"planned | in_progress | completed\",
    \"workout_date\": \"YYYY-MM-DD\",
    \"difficulty_level\": \"beginner | intermediate | advanced\",
    \"progress_results\": \"...\",
    \"tags\": \"...\",
    \"rating\": ...
  },
  \"exercises\": [
    {
      \"exercise_name\": \"...\",
      \"sets\": ...,
      \"reps\": ...,
      \"rest_time\": ...,
      \"category\": \"...\",
      \"muscle_group\": \"...\",
      \"description\": \"...\",
      \"video_url\": \"...\",
      \"difficulty_level\": \"...\",
      \"calories_burned\": ...,
      \"duration_seconds\": ...,
      \"intensity\": \"...\"
    }
  ]
}

#### For a weekly/multi-day plan:
[
\"workout_number\": ..., 
  {
    \"workout\": { ... same structure as above ... },
    \"exercises\": [ ... same structure as above ... ]
  },
  ...
]

Important:
- The field \"week_day\" is mandatory and used for planning the workout on specific days like \"Monday\", \"Tuesday\", etc.
- The field \"workout_number\" is mandatory:
    - If the number of workouts is **one**, set \"workout_number\": false.
    - If the number of workouts is **greater than one**, set \"workout_number\": true."


                            ]
                        ]
        ]);
    
        $data = $response->json();
        $rawJsonRespounse = $data['choices'][0]['message']['content'];
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
    
      // dd($rawRespounse);
    $cleaned = trim($rawRespounse);
    $cleaned = preg_replace('/^```json|```$/m', '', $cleaned);
    $cleaned = str_replace(['"""', '```'], '', $cleaned); // remove triple quotes or stray backticks
    $cleaned = trim($cleaned);
    
    $aiData = json_decode($cleaned, true);

    
    if (json_last_error() !== JSON_ERROR_NONE) {
        dd('JSON decode error:', json_last_error_msg(), $cleaned);
    }
    

    return  $aiData;
    
    }
    

}

