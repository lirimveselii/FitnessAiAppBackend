<?php

use App\Mail\VerifyEmail;
use App\Events\MessageSent;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PostController;
use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\HomeController;





// Authentication Routes 

Route::post('/register', [AuthController::class, 'register']);
Route::post("/login",[AuthController::class,"login"]);
Route::get('/logout', [AuthController::class,'logout'])->name('logout');
Route::get('/verify-email/{id}', [AuthController::class, 'verifyEmail']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
// Route::get('/reset-password/{id}', [AuthController::class, 'resetPassword']);
Route::post('meta-ai', [AiService::class, 'askHuggingFace']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/test-auth', function(){
            return response()->json(["message" => "the user is authenticated"]);
    });
    
});
Route::post('/generate-workout', [WorkoutController::class, 'generateWorkoutPlan']);
Route::get('/user-workouts', [WorkoutController::class, 'getAllUserWorkouts']);



Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {


});
Route::get('/hello', function (Request $request) {
    return response()->json(['message' => 'Hello,  World!'], 200);
});


Route::get('/test-ai', [OpenRouterController::class, 'testAI']);

Route::get('/ask', [WorkoutController::class, 'askHuggingFace']);
Route::get('/ask1', [WorkoutController::class, 'queryDeepSeek']);
Route::get('/get-all-user-workout', [WorkoutController::class, 'getAllUserWorkouts']);
Route::get('/test-broadcast', function () {
    event(new MessageSent('🔥 Hello from qa ka qa ska o pidh nane Laravel Reverb!'));
    return 'Broadcasted';
});

Route::get('/test-meals', [HomeController::class, 'todaysMeals']);