<?php 

namespace App\Services;
use App\Services\AiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Utils\AiResponseParser;



class AIService
{
    
   public function getWorkoutPlan(array $inputs): array
    {
        $prompt =  $this->buildWorkoutPrompt($inputs);
        $rawResponse = app(AiClient::class)->send($prompt);
        return AiResponseParser::clean($rawResponse);

    }

    public function getDietPlan(array $inputs){

        $prompt = $this->buildDietePrompt($inputs);
        $rawResponse = app(AiClient::class)->send($prompt);
        return AiResponseParser::clean($rawResponse);

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

public function buildDietePrompt($data) 
{
    $template = File::get(resource_path('prompts/diet_plan.txs'));


       $replacements = [
            '{{diet_goal}}' => $data['diet_goal'] ?? '',
            '{{dietary_preference}}' => $data['dietary_preference'] ?? '',
            '{{allergies}}' => is_array($data['allergies']) ? implode(', ', $data['allergies']) : $data['allergies'],
            '{{disliked_foods}}' => $data['disliked_foods'] ?? '',
            '{{meals_per_day}}' => $data['meals_per_day'] ?? 3,
            '{{daily_budget}}' => $data['daily_budget'] ?? 10,
            '{{region}}' => $data['region'] ?? '',
            '{{cooking_style}}' => $data['cooking_style'] ?? 'mixed',
            '{{prep_time_per_meal}}' => $data['prep_time_per_meal'] ?? '15–30 minutes',
            '{{track_macros}}' => $data['track_macros'] ?? 'no',
        ];

        return str_replace(array_keys($replacements), array_values($replacements), $template);
}
    

}

