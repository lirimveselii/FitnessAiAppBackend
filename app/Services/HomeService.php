<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class HomeService
{
    protected $apiKey;
    protected $baseUrl;

    public function __construct()
    {
        $this->apiKey = config('services.openweather.key'); // read from config
        $this->baseUrl = 'https://api.openweathermap.org/data/2.5/';
    }

    public function getWeatherByCity(string $city)
    {
        $url = $this->baseUrl . "weather?q={$city}&appid={$this->apiKey}&units=metric";

        $response = Http::get($url);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }
}
