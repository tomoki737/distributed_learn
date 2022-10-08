<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

Route::get('/{any}', function() {
    return view('app');
})->where('any', '.*');

Route::post('/login',[LoginController::class, 'login']);
Route::post('/logout',[LoginController::class, 'logout']);
Route::post('/register',[App\Http\Controllers\RegisterController::class, 'register']);

Route::prefix('login')->name('login.')->group(function() {
    Route::get('social/{provider}', [LoginController::class, 'redirectToProvider'])->name('{provider}');
});
