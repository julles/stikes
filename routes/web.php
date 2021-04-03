<?php

// use Illuminate\Http\Request;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use App\Models\Dosen;

Route::view('/', 'auth.login');

Auth::routes(['password.update' => false]);

Route::post('/password/reset', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:6|confirmed',
    ]);

    $newPassword = Hash::make($request->password);

    $user = Dosen::where('email',$request->email)
                   ->update(
                            ['password'=>$newPassword,
                             'password_plain'=>$request->password,
                             'remember_token'=>Str::random(60)
                            ]);

    return redirect('login')->with(['type'=>'success','message'=>'Password updated successfully!']);

})->middleware('guest')->name('password.update');


Route::get('/home', 'HomeController@home')->name('home')->middleware('auth');

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