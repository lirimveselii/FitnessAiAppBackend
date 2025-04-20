
<?php
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});


// Auth::routes(['verify' => true]);


Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

// Linku që vjen në email
Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
    $request->fulfill(); // konfirmon verifikimin

    return redirect('/home');
})->middleware(['auth', 'signed'])->name('verification.verify');

// Dërgon linkun për verifikim përsëri
Route::post('/email/verification-notification', function (Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');

// Shembull i një route që kërkon verifikim
Route::get('/profile', function () {
    return view('profile');
})->middleware(['auth', 'verified']); // <--- kërkon që user-i të jetë verified