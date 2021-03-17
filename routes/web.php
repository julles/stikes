<?php

Route::view('/', 'auth.login');

Auth::routes();

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::middleware('auth')->group(function () {
    AdvancedRoute::controllers([
        '/dosen' => 'DosenController',
        '/user-management' => 'UserManagementController',
    ]);
});
