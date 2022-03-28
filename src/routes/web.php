<?php

use Illuminate\Support\Facades\Route;

Route::get('/{any}', function() {
    return view('app');
})->where('any', '.*');

Route::post('/login',[App\Http\Controllers\LoginController::class, 'login']);
Route::post('/logout',[App\Http\Controllers\LoginController::class, 'logout']);
Route::post('/register',[App\Http\Controllers\RegisterController::class, 'register']);
