<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\PostController;
use App\Http\Middleware\RoleMiddleware;


Route::post('/register', [AuthController::class, 'register']);
Route::post("/login",[AuthController:: class,  "login"]);


Route::middleware('auth:sanctum')->group(function () {
    Route::get("/test-login",[AuthController:: class,  "testIfLogdIn"]);

    Route::get('/test-admin', function () {
    return 'Përmbajtje vetëm për admin!';
})->middleware(RoleMiddleware::class . ':admin');

});

Route::middleware("auth:sanctum")->group(function(){
    Route::resource("posts", PostController::class);
});
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');
Route::get('/admin/dashboard',[AuthController::class, 'index']);

Route::get('/admin', function () {
    return 'Admin only zone!';
})->middleware(RoleMiddleware::class . ':admin');



