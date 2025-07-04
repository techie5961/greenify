<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersAuthController;
use App\Http\Controllers\UsersPostRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('login',[
    UsersAuthController::class,'Login'
]);
Route::get('register',[
    UsersAuthController::class,'Register'
]);
Route::prefix('users')->group(function(){





    // USERS POST REQUEST
    Route::prefix('post')->group(function(){
        Route::post('register/process',[
            UsersPostRequestController::class,'Register'
        ]);
         Route::post('login/process',[
            UsersPostRequestController::class,'Login'
        ]);
    });
});
