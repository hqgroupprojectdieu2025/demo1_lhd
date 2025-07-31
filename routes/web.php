<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\SignupController;
use App\Http\Controllers\Auth\LoginController;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/register', [SignupController::class, 'showRegistrationForm'])->name('register.form');
Route::post('/register', [SignupController::class, 'register'])->name('register');
Route::post('/resend-verification-email', [SignupController::class, 'resendVerificationEmail'])->name('resend.verification.email');
Route::get('/verify-email/{id}/{hash}', [SignupController::class, 'verify'])->name('verify.email');
Route::get('/check-verification-status/{id}', [SignupController::class, 'checkVerificationStatus'])->name('check.verification.status');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout',[LoginController::class, 'logout'])->name('logout');