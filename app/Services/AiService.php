<?php 

namespace App\Services;
use Illuminate\Support\Facades\Http;


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
    


    
    public function getWorkoutPlan($userType,$fitnessGoal,$workoutDaysPerWeek,$fitnessLevel,$focusArea,$availableEquipment,$workoutTypePreference,$targetWeightGoal,$injuriesOrLimitations,$workoutTimePerSession,$structuredOrFlexiblePlan,$includeNutritionPlan)
    {

        $focusAreaString = implode(', ', $focusArea);
        // $availableEquipmentString = implode(', ', $availableEquipment); // in review to see if it is neaded
        $injuriesOrLimitationsString = implode(', ', $injuriesOrLimitations);

      
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
                                "content" => "Create a personalized fitness workout plan based on the following user input:
            
            {
                \"user_type\": \"$userType\",
                \"fitness_goal\": \"$fitnessGoal\",
                \"workout_days_per_week\": $workoutDaysPerWeek,
                \"fitness_level\": \"$fitnessLevel\",
                \"focus_area\": \"$focusAreaString\",
                \"workout_type_preference\": \"$workoutTypePreference\",
                \"target_weight_goal\": $targetWeightGoal,
                \"injuries_or_limitations\": \"$injuriesOrLimitationsString\",
                \"workout_time_per_session\": $workoutTimePerSession,
                \"structured_or_flexible_plan\": \"$structuredOrFlexiblePlan\",
                \"include_nutrition_plan\": \"$includeNutritionPlan\"
            }
            The field 'workout_day' is MANDATORY in every workout. It MUST be filled with a valid weekday like 'Monday', 'Tuesday', etc. DO NOT OMIT IT.

            Please create a workout plan that includes **4-5 exercises** based on the user's fitness level:
            - For **beginner**: Include simpler exercises like bodyweight exercises or light weights.
            - For **intermediate**: Add moderate-intensity exercises with weights, more sets/reps.
            - For **advanced**: Add complex movements and exercises that require higher intensity, longer duration, and more weight.
            
            The number of exercises should align with the user's fitness level.
            
            - If the user wants a single workout session, return **just one workout object** with its exercises.
            - If the user wants a plan for multiple days (e.g., a week), return an **array of workout plans**, one per day.
            - Every workout **must include** the \"workout_day\" field to specify on which day it is planned (e.g., \"Monday\", \"Tuesday\", etc.). it is mendatory
            - The structure and field names must always be exactly the same, with all fields filled.
            - All fields in both the \"workout\" and \"exercises\" sections are mandatory.
            - The output must be **valid JSON** with no additional characters, comments, explanations, or markdown formatting — only clean, parsable JSON compatible with json_decode.
            
            --- 

            ### JSON FORMAT:

            #### For a single workout:
            {
                \"workout\": {
                    \"title\": \"...\",
                    \"description\": \"...\",
                    \"workout_day\": \"...\",The field \"workout_day\" is mandatory and used for planning the workout on specific days like \"Monday\", \"Tuesday\", etc.
                    \"duration_min\": ...,
                    \"intensity_level\": \"low | moderate | high\",
                    \"workout_type\": \"Monday | ...\",
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
            {
                {
                    \"workout\": { ... same structure as above ... },
                    \"exercises\": [ ... same structure as above ... ]
                },
                ...
            }

            Important:
            - The field \"workout_day\" is mandatory and used for planning the workout on specific days like \"Monday\", \"Tuesday\", etc.
            "


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
    

}

