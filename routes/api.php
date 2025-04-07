<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\WorkoutController;

Route::get('/user', function (Request $request) {
    return response()->json(['message' => 'Hello,  Worl d!'], 200);
});

Route::get('/test-ai', [OpenRouterController::class, 'testAI']);

Route::get('/ask', [WorkoutController::class, 'askHuggingFace']);
Route::get('/ask1', [WorkoutController::class, 'queryDeepSeek']);
Route::post('/generate-workout', [WorkoutController::class, 'generateWorkoutPlan']);


