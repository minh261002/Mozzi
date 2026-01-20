<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('dashboard');

Route::get('/login', function () {
    return view('auth.login');
});

Route::post('/login', function () { 
    return redirect()->route('dashboard');
})->name('authenticate');

Route::get('/password/forgot', function () {
    return view('auth.forgot-password');
})->name('password.forgot');

Route::post('/password/forgot', function () {
    return redirect()->route('password.reset');
})->name('password.email');

Route::get('/password/reset/{token}', function () {
    return view('auth.reset-password');
})->name('password.reset');

Route::post('/password/reset', function () {
    return redirect()->route('dashboard');
})->name('password.update');