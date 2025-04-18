<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Middleware\RoleMiddleware;


// Route::get('/user', function (Request $request) {
//     return response()->json(['message' => 'Hello,  Worl d!'], 200);
// });
// Route::post("/register", [AuthController::class, "register"]);


// Route::get('/test', function () {
//     return 'welcome';
// });



Route::post('/register', [AuthController::class, 'register']);
Route::post("/login",[AuthController:: class,  "login"]);
Route::middleware("auth:sanctum")->group(function(){
    Route::resource("posts", PostController::class);
});
Route::get('/logout', [YourControllerName::class, 'logout'])->name('logout');
Route::get('/admin/dashboard',[AuthController::class, 'index']);

Route::get('/admin', function () {
    return 'Admin only zone!';
})->middleware(RoleMiddleware::class . ':admin');

// Route::get('/coach', function () {
//     return 'Coach only zone!';
// })->middleware(RoleMiddleware::class . ':coach');

Route::get('/test-admin', function () {
    return 'Përmbajtje vetëm për admin!';
})->middleware(RoleMiddleware::class . ':admin');