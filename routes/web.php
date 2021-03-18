<?php

Route::view('/', 'auth.login');

Auth::routes();
// dd(\Hash::make('123'));

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::middleware('auth')->group(function () {
    AdvancedRoute::controllers([
        '/dosen' => 'DosenController',
        '/assign-dosen' => 'AssignDosenController',
        '/input-text-book' => 'InputTextBookController',
        '/user-management' => 'UserManagementController',
    ]);
});
