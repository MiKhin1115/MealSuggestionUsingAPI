<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/register', function () {
    return view('register');
})->middleware('guest');

Route::get('/register-questions', function () {
    return view('register-questions');
})->middleware('guest');

Route::get('/register-questions-2', function () {
    return view('register-questions-2');
})->middleware('guest');

Route::get('/register-questions-3', function () {
    return view('register-questions-3');
})->middleware('guest');

Route::get('/login', function () {
    return view('login');
})->middleware('guest');

Route::get('/dashboard', function () {
    return view('dashboard');
})->name('dashboard');

Route::get('/about', function () {
    return view('about');
});

Route::get('/registration-success', function () {
    return view('registration-success');
});//->middleware('auth')->name('registration.success');

Route::post('/logout', function () {
    Auth::logout();
    return redirect('/');
})->middleware('auth')->name('logout');
