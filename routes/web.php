<?php

Route::view('/', 'auth.login');

Auth::routes();

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');
