<?php 

namespace App\Services;
use App\Services\AiClient;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use App\Utils\AiResponseParser;



class AIService
{
    
public function getAiWorkoutPlan(array $inputs): array
{
    $prompt = $this->buildPrompt('/prompts/workout_plan.txs', $inputs);
    $rawResponse = app(AiClient::class)->send($prompt);
    return AiResponseParser::clean($rawResponse);
}

public function getAiDietPlan(array $inputs): array //in progress
{
    $prompt = $this->buildPrompt('/prompts/diet_plan.txs', $inputs);
    $rawResponse = app(AiClient::class)->send($prompt);
    return AiResponseParser::clean($rawResponse);
}

public function estimateMealMacro(array $input){

    $prompt = $this->buildPrompt("/prompts/estemated_macros_from_ai.txs", $input);
    $rawResponse = app(AiClient::class)->send($prompt);
    return AiResponseParser::clean($rawResponse);
        

} 



public function buildPrompt(string $templatePath,  $params): string
{
    $template = File::get(resource_path($templatePath));

    foreach ($params as $key => $value) { 

        if (is_array($value)) {
            $value = json_encode($value); // or implode(', ', $value) depending on usage
        }
        $template = str_replace('{{' . $key . '}}', $value, $template);
    }
    return $template;
}


    

}

