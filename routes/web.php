<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrgController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\EventController;

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

// Event routes - Public viewing
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/events/{eventId}', [EventController::class, 'show'])->name('events.show');

// Event RSVP routes
Route::post('/events/{eventId}/rsvp', [EventController::class, 'rsvp'])->name('events.rsvp');
Route::delete('/events/{eventId}/rsvp', [EventController::class, 'cancelRsvp'])->name('events.cancelRsvp');

// Event management routes - For officers/advisers
Route::post('/organization/{orgId}/events/create', [EventController::class, 'createEvent'])->name('events.create');
Route::put('/events/{eventId}/update', [EventController::class, 'updateEvent'])->name('events.update');
Route::delete('/events/{eventId}/delete', [EventController::class, 'deleteEvent'])->name('events.delete');
Route::post('/events/{eventId}/approve', [EventController::class, 'approveEvent'])->name('events.approve');
Route::post('/events/{eventId}/reject', [EventController::class, 'rejectEvent'])->name('events.reject');

// Organization event management routes (for orgdetail page)
Route::post('/organization/{id}/event/create', [OrgController::class, 'createEvent'])->name('organization.createEvent');
Route::post('/organization/{orgId}/event/{eventId}/approve', [OrgController::class, 'approveEvent'])->name('organization.approveEvent');
Route::post('/organization/{orgId}/event/{eventId}/reject', [OrgController::class, 'rejectEvent'])->name('organization.rejectEvent');

// Organization membership routes
Route::post('/organization/{id}/join', [OrgController::class, 'joinOrganization'])->name('organization.join');
Route::post('/organization/{id}/leave', [OrgController::class, 'leaveOrganization'])->name('organization.leave');
Route::post('/organization/{id}/cancel-request', [OrgController::class, 'cancelMembershipRequest'])->name('organization.cancelRequest');

// Membership form submission
Route::post('/organization/{id}/membership', [OrgController::class, 'submitMembershipForm'])->name('organization.submitMembership');

// Officer/Adviser actions
Route::post('/organization/{orgId}/member/{membershipId}/approve', [OrgController::class, 'approveMember'])->name('organization.approveMember');
Route::post('/organization/{orgId}/member/{membershipId}/reject', [OrgController::class, 'rejectMember'])->name('organization.rejectMember');
Route::post('/organization/{orgId}/member/add', [OrgController::class, 'addMember'])->name('organization.addMember');
Route::post('/organization/{orgId}/event/{eventId}/cancel', [OrgController::class, 'cancelEvent'])
    ->name('organization.cancelEvent');

Route::get('/membership', function () {
    return view('Pages.memberform');
})->name('membership');

// Profile routes
Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
Route::get('/profile/{userId}', [ProfileController::class, 'show'])->name('profile.show');

// Settings routes
Route::get('/settings', [SettingsController::class, 'index'])->name('settings');
Route::post('/settings/update', [SettingsController::class, 'update'])->name('settings.update');

// Reference routes
Route::get('/dashboard2', function () {
    return view('pureHTML.referencedash1');
})->name('reference.dashboard');

Route::get('/orgDetailRef', function () {
    return view('pureHTML.refOrgDetail');
})->name('reference.orgDetail');