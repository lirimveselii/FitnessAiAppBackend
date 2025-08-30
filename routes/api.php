<?php
use App\Mail\VerifyEmail;
use App\Events\MessageSent;
use App\Services\AiService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use App\Http\Middleware\RoleMiddleware;
use App\Http\Controllers\WorkoutController;
use App\Http\Controllers\ExerciseController;
use App\Http\Controllers\FoodController;
use App\Http\Controllers\DietPlanController;
use App\Http\Controllers\WorkoutLogController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\PostController;
use App\Http\Controllers\OpenRouterController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\GoalController;
use App\Http\Controllers\ManualMealLogController;




// Authentication Routes 

Route::post('/register', [AuthController::class, 'register']);
Route::post("/login",[AuthController::class,"login"]);
Route::get('/logout', [AuthController::class,'logout'])->name('logout');
Route::get('/verify-email/{id}', [AuthController::class, 'verifyEmail']);
Route::post('/forget-password', [AuthController::class, 'forgetPassword']);

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('/test-auth', function(){
        return response()->json(["message" => "the user is authenticated"]);
    });

});

//Workout routes
Route::post('/generate-workout', [WorkoutController::class, 'generateWorkoutPlan']);
Route::get('/user-workouts', [WorkoutController::class, 'getAllUserWorkouts']);
Route::post('/create-workout', [WorkoutController::class, 'storeCustomWorkout']);
Route::put('/update-workout/{workout}', [WorkoutController::class, 'updateWorkout']);
Route::delete('/delete-workout/{workout}', [WorkoutController::class, 'deleteWorkout']);
Route::get('/filter-exercises', [ExerciseController::class, 'filterExercises']);
Route::post('/store-workout-logs', [WorkoutLogController::class, 'storeWorkoutLogs']);
Route::post('estimated-meal-cal-ai', [ManualMealLogController::class, 'estimatedCaloriesCount']);
Route::get('search-exercise', [ExerciseController::class, 'searchExercise']);

// "Admin" Workout routes
Route::get('/admin/muscle-group-connect', [ExerciseController::class, 'filterExercises']);








// Route::post('/generate-diet', [MealPlanController::class, 'generateDiet']);
Route::post('/generate-diet', [DietPlanController::class, 'generateDiet']);
Route::get('food-library', [DietPlanController::class, 'search']);
Route::get('/get-user-diet', [MealPlanController::class, 'getFullDiet']);



//Adimin routes
Route::middleware([RoleMiddleware::class . ':admin'])->prefix('admin')->group(function () {    


});



// Test Routs
Route::get('/get-all-user-workout', [WorkoutController::class, 'getAllUserWorkouts']);
Route::get('/test-ai', [OpenRouterController::class, 'testAI']);
Route::get('/ask', [WorkoutController::class, 'askHuggingFace']);
Route::get('/ask1', [WorkoutController::class, 'queryDeepSeek']);
Route::get('/test-broadcast', function () {
    event(new MessageSent('🔥 Hello from qa ka qa ska o pidh nane Laravel Reverb!'));
    return 'Broadcasted';
});


Route::get('/test-meals', [HomeController::class, 'todaysMeals']);
Route::get('/google-ai', [AiService::class, 'testGoogleAi']);

// Goals
Route::post('/store_goal', [GoalController::class, 'store']);
Route::get('/goals/{id}', [GoalController::class, 'show']);
Route::put('/update_goals/{id}', [GoalController::class, 'update']);
Route::delete('/goals/{id}', [GoalController::class, 'destroy']);






?>