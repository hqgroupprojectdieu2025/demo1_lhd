<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Auth;

// Public routes
Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::get('/register', [SignupController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [SignupController::class, 'register'])->name('register');
Route::post('/resend-verification-email', [SignupController::class, 'resendVerificationEmail'])->name('resend.verification.email');
Route::get('/verify-email/{id}/{hash}', [SignupController::class, 'verify'])->name('verify.email');
Route::get('/check-verification-status/{id}', [SignupController::class, 'checkVerificationStatus'])->name('check.verification.status');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

// Check lockout status
Route::post('/check-lockout-status', [LoginController::class, 'checkLockoutStatus'])->name('check.lockout.status');

// 2FA routes
Route::get('/2fa', [LoginController::class, 'show2FAForm'])->name('2fa.form');
Route::post('/2fa/verify', [LoginController::class, 'verify2FA'])->name('2fa.verify');
Route::post('/2fa/resend', [LoginController::class, 'resend2FA'])->name('2fa.resend');

// Protected routes
Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', function () {
        if (Auth::user()->account_type == 0) {
            return view('dashboard');
        }
        return view('welcome');
    })->name('dashboard');
    
    Route::post('/logout', [LoginController::class, 'logout'])->name('logout');
    
    // 2FA Setup routes (cần đăng nhập)
    Route::get('/2fa/setup', [App\Http\Controllers\Auth\TwoFAController::class, 'showSetupForm'])->name('2fa.setup');
    Route::post('/2fa/enable', [App\Http\Controllers\Auth\TwoFAController::class, 'enable2FA'])->name('2fa.enable');
    Route::post('/2fa/disable', [App\Http\Controllers\Auth\TwoFAController::class, 'disable2FA'])->name('2fa.disable');
    Route::get('/2fa/regenerate', [App\Http\Controllers\Auth\TwoFAController::class, 'regenerateSecret'])->name('2fa.regenerate');
});