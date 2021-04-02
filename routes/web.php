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
        '/review-text-book' => 'ReviewTextBookController',
        '/user-management' => 'UserManagementController',
        '/metode-penilaian' => 'MetodePenilaianController',
        '/rps' => 'RpsController',
        '/review-rps' => 'ReviewRpsController',
        '/or' => 'OrController',
        '/review-or' => 'ReviewOrController',
        '/semester' => 'SemesterController',
        '/mata-kuliah' => 'MataKuliahController',
    ]);

    // OR Question
    Route::get('/or/detail/{id}/question', 'OrController@question');
    Route::post('/or/detail/{id}/question', 'OrController@questionStore');
    Route::delete('/or/detail/{id}/question', 'OrController@deleteQuestion');
    Route::patch('/or/detail/{id}/question', 'OrController@updateQuestion');
    
    Route::get('/or/detail/{id}/summary', 'OrController@summary');

    Route::get('/rps/view-pdf/{type}/{file}', 'RpsController@viewPdf');
});

Route::get("parse-str", function () {
    return request()->all();
});


Route::get('/test-email', 'RpsController@testEmail');