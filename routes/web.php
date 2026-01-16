<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\OrgController;

Route::get('/', function () {
    return view('welcome');
});



Route::get('/login', function () {
    return view('authfolder.signin');
});

Route::get('/signup', function () {
    return view('authfolder.signup');
})->name('signup');

Route::get('/dashboard', [OrgController::class, 'index'])->name('dashboard');


Route::get('/membership', function () {
    return view('afterloginfolder.memberform');
});

/*Route::get('/dashboard', function () {
    return view('afterloginfolder.dashboard');
});*/


Route::get('/orgDetail', function () {
    return view('afterloginfolder.orgDetail');
})->name('orgDetail');


#----REFERENCES URL--
Route::get('/dashboard2', function () {
    return view('pureHTML.dash1');
});

Route::get('/orgDetailRef', function () {
    return view('pureHTML.refOrgDetail');
});
