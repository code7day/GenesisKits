<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
<<<<<<< HEAD
    Route::get('/me', function () {
        return view('dashboard');
    })->name('me');
=======
    Route::get('/admin', function () {
        return view('dashboard');
    })->name('dashboard');
>>>>>>> 286a935 (fix)
});
