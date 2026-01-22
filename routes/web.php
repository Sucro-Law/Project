<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Mail;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Auth routes
Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Forgot/Reset Password routes
Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

// Pages
Route::get('/dashboard', [OrgController::class, 'index'])->name('dashboard');
Route::get('/organization', [OrgController::class, 'organization'])->name('organization');
Route::get('/events', [OrgController::class, 'events'])->name('events');

Route::get('/membership', function () {
    return view('Pages.memberform');
})->name('membership');

Route::get('/organization/detail', function () {
    return view('Pages.orgDetail');
})->name('orgDetail');

Route::get('/profile', function () {
    return view('Pages.profile');
})->name('profile');

Route::get('/settings', function () {
    return view('Pages.settings');
})->name('settings');

// Reference URLs (for development)
Route::get('/dashboard2', function () {
    return view('pureHTML.referencedash1');
});

Route::get('/orgDetailRef', function () {
    return view('pureHTML.refOrgDetail');
});

Route::get('/test-email', function () {
    try {
        Mail::raw('This is a test email from Laravel', function ($message) {
            $message->to('johnevansgutierrez9@gmail.com')
                    ->subject('Test Email');
        });
        return 'Email sent! Check your inbox and spam folder.';
    } catch (\Exception $e) {
        return 'Email failed: ' . $e->getMessage();
    }
});