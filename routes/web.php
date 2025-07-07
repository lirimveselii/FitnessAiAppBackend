
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\ExerciseAliasController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reset-password-view',[AuthController::class,'resetPasswordView']);
Route::post('/update-password', [AuthController::class, 'resetPassword'])->name('password.update');
Route::get('/create-alias', [ExerciseAliasController::class, 'create']);
Route::post('/store-alias', [ExerciseAliasController::class, 'store'])->name('alias.store');



