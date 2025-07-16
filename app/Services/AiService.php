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

        $prompt = $this->buildDietPrompt($inputs);
        // dd($prompt);
        $rawResponse = app(AiClient::class)->send($prompt);
        return  AiResponseParser::clean($rawResponse);

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

public function buildDietPrompt(array $params): string
{
    $template = File::get(resource_path('/prompts/diet_plan.txs'));

    $replacements = [
        '{{diet_goal}}' => $params['diet_goal'],
        '{{plan_duration_days}}' => $params['plan_duration_days'],
        '{{calorie_goal_source}}' => $params['calorie_goal_source'],
        '{{calorie_target}}' => $params['calorie_target'],
        '{{macros_source}}' => $params['macros_source'],
        '{{protein_percent}}' => $params['protein_percent'],
        '{{carbs_percent}}' => $params['carbs_percent'],
        '{{fats_percent}}' => $params['fats_percent'],
        '{{meals_per_day}}' => $params['meals_per_day'],
        '{{fasting_enabled}}' => $params['fasting_enabled'] ? 'true' : 'false',
        '{{diet_styles}}' => is_array($params['diet_styles']) ? json_encode($params['diet_styles']) : $params['diet_styles'],
        '{{dietary_restrictions}}' => is_array($params['dietary_restrictions']) ? json_encode($params['dietary_restrictions']) : $params['dietary_restrictions'],
        '{{has_religious_restrictions}}' => $params['has_religious_restrictions'] ? 'true' : 'false',
        '{{religious_type}}' => $params['religious_type'],
        '{{budget_level}}' => $params['budget_level'],
        '{{language}}' => $params['language'],
    ];

    return str_replace(array_keys($replacements), array_values($replacements), $template);
}


    

}

