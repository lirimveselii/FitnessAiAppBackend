
<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/reset-password-view',[AuthController::class,'resetPasswordView']);
Route::post('/update-password', [AuthController::class, 'resetPassword'])->name('password.update');

