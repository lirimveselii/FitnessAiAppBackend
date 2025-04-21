<?php

use App\Mail\VerifyEmail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\WorkoutController;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;




// Authentication Routes 
Route::post('/register', [AuthController::class, 'register']);
Route::post("/login",[AuthController:: class,  "login"]);
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/verify-email/{id}', [AuthController::class, 'verifyEmail']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);
// Route::get('/reset-password/{id}', [AuthController::class, 'resetPassword']);



Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {

//Only the routes that the admin have access


});
Route::get('/test-ai', [OpenRouterController::class, 'testAI']);

Route::get('/ask', [WorkoutController::class, 'askHuggingFace']);
Route::get('/ask1', [WorkoutController::class, 'queryDeepSeek']);
Route::post('/generate-workout', [WorkoutController::class, 'generateWorkoutPlan']);
Route::get('/get-all-user-workout', [WorkoutController::class, 'getAllUserWorkouts']);
