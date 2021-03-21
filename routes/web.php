<?php

use Illuminate\Http\Request;

Route::view('/', 'auth.login');

Auth::routes();

Route::get('/home', function () {
    return view('home');
})->name('home')->middleware('auth');

Route::middleware('auth')->group(function () {
    AdvancedRoute::controllers([
        '/dosen' => 'DosenController',
        '/assign-dosen' => 'AssignDosenController',
        '/input-text-book' => 'InputTextBookController',
        '/user-management' => 'UserManagementController',
        '/rps' => 'RpsController',
        '/semester' => 'SemesterController',
        '/mata-kuliah' => 'MataKuliahController',
    ]);
});

Route::get("parse-str", function () {
    return request()->all();
});
