<?php

use App\Events\MessageSent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\OpenRouterController;


Route::get('/hello', function (Request $request) {
    return response()->json(['message' => 'Hello,  World!'], 200);
});

Route::get('/test-ai', [OpenRouterController::class, 'testAI']);

Route::get('/ask', [WorkoutController::class, 'askHuggingFace']);
Route::get('/ask1', [WorkoutController::class, 'queryDeepSeek']);
Route::post('/generate-workout', [WorkoutController::class, 'generateWorkoutPlan']);
Route::get('/get-all-user-workout', [WorkoutController::class, 'getAllUserWorkouts']);
Route::get('/test-broadcast', function () {
    event(new MessageSent('🔥 Hello from Laravel Reverb!'));
    return 'Broadcasted';
});




