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

// Dashboard and other pages
Route::get('/dashboard', [OrgController::class, 'index'])->name('dashboard');

Route::get('/membership', function () {
    return view('afterloginfolder.memberform');
})->name('membership');

Route::get('/orgDetail', function () {
    return view('afterloginfolder.orgDetail');
})->name('orgDetail');

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