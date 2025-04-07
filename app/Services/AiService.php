<?php 

namespace App\Services;
use Illuminate\Support\Facades\Http;


class AIService
{

    public function getWorkoutPlan($prount)
    {
        $apiKey = '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'; 
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])->post('https://api-inference.huggingface.co/v1/chat/completions', [
            'provider' => 'novita',
            'model' => 'Qwen/QwQ-32B',
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prount ,
                ],
            ],
            'max_tokens' => 500,
        ]);
    
        $data = $response->json();
    
        if (isset($data['choices'][0]['message']['content'])) {
    
           $rawRespounse =   $this->queryDeepSeek($data['choices'][0]['message']['content']);
    
           $jsonRespounse = $this->trimRespounse($rawRespounse);

    
        //    dd($res);
    
            return  $jsonRespounse;
         
        } else {
            return response()->json([
                'error' => 'No answer found.',
                'raw' => $data
            ], 500);
        }
    }
    
    public function queryDeepSeek($prount)
    {
    
    
        // dd($prount);
        $apiKey = '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'; 
    
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])
        ->timeout(60)
        ->post('https://router.huggingface.co/together/v1/chat/completions', [
            'model' => 'deepseek-ai/DeepSeek-V3',
            'max_tokens' => 700,
            'messages' => [
                [
                    "role" => "user",
                    "content" => "Create a personalized fitness workout plan based on the following preferences: $prount. I need only a clean, valid JSON response without any explanation or formatting. The JSON should contain all necessary fields to be stored in my fitness app's database.
                
                The structure should match two models:
                
                1. **Workout** (one entry):
                - title (string)
                - description (text)
                - duration_min (integer)
                - intensity_level (low, moderate, or high)
                - workout_type (e.g., cardio, strength)
                - calories_burned (decimal)
                - target_muscle_groups (comma-separated string, e.g., 'chest, legs')
                - notes (optional string)
                - status (planned, in_progress, or completed)
                - workout_date (YYYY-MM-DD format)
                - difficulty_level (beginner, intermediate, advanced)
                - progress_results (optional string)
                - tags (comma-separated string, e.g., 'HIIT, fat-burn')
                - rating (integer 1-5)
                
                2. **Exercises** (array of objects inside the workout):
                - sets (integer)
                - reps (integer)
                - rest_time (in seconds)
                - exercise_id (integer or string, just for demo mapping)
                - order (integer, sequence number)
                - notes (optional)
                
                Return a single JSON object like:
                {
                  \"workout\": { ... },
                  \"exercises\": [ { ... }, { ... } ]
                }
                
                Do not return anything except the JSON response pure in order so i can json_decode it no extra characters or spaces just json  ."
                ],
            ],
        ]);
    
        $data = $response->json();
    
        if (isset($data['choices'][0]['message']['content'])) {
    
            return $data['choices'][0]['message']['content'];
    
        } else {
            return response()->json([
                'error' => 'No answer returned.',
                'details' => $data
            ], 500);
        }
    }
    
    
    public function trimRespounse($rawRespounse){
    
    $cleaned = trim($rawRespounse);
    $cleaned = preg_replace('/^```json|```$/m', '', $cleaned);
    $cleaned = str_replace(['"""', '```'], '', $cleaned); // remove triple quotes or stray backticks
    $cleaned = trim($cleaned);
    
    $aiData = json_decode($cleaned, true);
    
    if (json_last_error() !== JSON_ERROR_NONE) {
        dd('JSON decode error:', json_last_error_msg(), $cleaned);
    }
    
    // dd($aiData);

    return  $aiData;
    
    }
    

}

