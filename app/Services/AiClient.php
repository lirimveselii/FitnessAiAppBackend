<?php
 namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiClient {


   protected string $baseUrl;
    protected string $model;


    public function __construct(){
         $this->baseUrl = 'https://api.together.xyz/v1/chat/completions';


        $this->model = 'meta-llama/Llama-4-Maverick-17B-128E-Instruct-FP8';
        // $this->model = 'meta-llama/Llama-3.3-70B-Instruct-Turbo-Free';
        // $this->model = 'deepseek-ai/DeepSeek-R1-0528-tput';

        

    }

public function send(string $prompt): string
{
    $apiKey = env('TOGETEHR_API_KEY');

    try {
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type'  => 'application/json',
        ])
        ->timeout(200)
        ->post($this->baseUrl, [
            'model' => $this->model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ]
        ]);

        $responseData = $response->json();

        // ✅ Step 1: Check for API-level error (e.g. rate limit)
        if (isset($responseData['error'])) {
            Log::error('AI model returned error', ['response' => $responseData]);
            throw new \Exception($responseData['error']['message'] ?? 'Unknown AI error');
        }

        // ✅ Step 2: Validate response format
        if (!isset($responseData['choices'][0]['message']['content'])) {
            Log::error('AI response missing expected content', ['response' => $responseData]);
            throw new \Exception('AI returned an invalid response format.');
        }

        // ✅ Step 3: Return clean content
        return $responseData['choices'][0]['message']['content'];

    } catch (\Throwable $e) {
        Log::error('AI call failed', ['error' => $e->getMessage()]);
        throw new \Exception('AI request failed: ' . $e->getMessage());
    }
}

}