<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
Route::post('/login', [AuthController::class, 'login'])->name('login.submit');

Route::get('/signup', [AuthController::class, 'showSignup'])->name('signup');
Route::post('/signup', [AuthController::class, 'signup'])->name('signup.submit');

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

Route::get('/forgot-password', [AuthController::class, 'showForgotPassword'])->name('password.request');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPassword'])->name('password.reset');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');

Route::get('/dashboard', [OrgController::class, 'index'])->name('dashboard');
Route::get('/organization', [OrgController::class, 'organization'])->name('organization');
Route::get('/organization/detail/{id}', [OrgController::class, 'show'])->name('orgDetail');
Route::get('/events', [OrgController::class, 'events'])->name('events');

Route::post('/organization/{id}/join', [OrgController::class, 'joinOrganization'])->name('organization.join');
Route::post('/organization/{id}/leave', [OrgController::class, 'leaveOrganization'])->name('organization.leave');
Route::post('/organization/{id}/cancel-request', [OrgController::class, 'cancelMembershipRequest'])->name('organization.cancelRequest');

// Officer/Adviser actions
Route::post('/organization/{orgId}/member/{membershipId}/approve', [OrgController::class, 'approveMember'])->name('organization.approveMember');
Route::post('/organization/{orgId}/member/{membershipId}/reject', [OrgController::class, 'rejectMember'])->name('organization.rejectMember');
Route::post('/organization/{orgId}/member/add', [OrgController::class, 'addMember'])->name('organization.addMember');

Route::get('/membership', function () {
    return view('Pages.memberform');
})->name('membership');

Route::get('/profile', function () {
    return view('Pages.profile');
})->name('profile');

Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

Route::get('/dashboard2', function () {
    return view('pureHTML.referencedash1');
})->name('reference.dashboard');

Route::get('/orgDetailRef', function () {
    return view('pureHTML.refOrgDetail');
})->name('reference.orgDetail');