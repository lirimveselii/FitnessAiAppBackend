<?php
namespace App\Services;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiClient
{


    protected string $baseUrl;
    protected string $model;


    public function __construct()
    {
        $this->baseUrl = config('services.together.base_url', 'https://api.together.xyz/v1/chat/completions');
        $this->model = config('services.together.model', 'meta-llama/Llama-3.3-70B-Instruct-Turbo-Free');
    }

    public function send(string $prompt): string
    {
        $apiKey = config('services.together.api_key', env('TOGETHER_API_KEY'));

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type' => 'application/json',
            ])
                ->timeout((int) config('services.together.timeout', 200))
                ->retry((int) config('services.together.retries', 0), (int) config('services.together.retry_delay', 100))
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