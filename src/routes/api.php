<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AnswerController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\UserController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [MeController::class, 'me']);
});

Route::get('/guest', [LoginController::class, 'guestLogin']);

Route::get('/home', [QuestionController::class, 'indexHome']);

Route::resource('/question', QuestionController::class)->except(['show']);
Route::post('/question/search', [QuestionController::class, 'search']);
Route::get('/question/search', [QuestionController::class, 'indexSearch']);
Route::post('/question/download', [QuestionController::class, 'downloadQuestion']);
Route::put('/question/{question}/except', [QuestionController::class, 'exceptQuestion']);

Route::prefix('question')->group(function () {
    Route::put('/{question}/like', [QuestionController::class, 'like']);
    Route::delete('/{question}/unlike', [QuestionController::class, 'unlike']);
});

Route::put('/question/{question}/answer', [AnswerController::class, 'answer']);
Route::get('/answer', [AnswerController::class, 'indexAnswer']);
Route::get('/answer/select', [AnswerController::class, 'indexSelectAnswer']);

Route::prefix('user')->group(function () {
    Route::get('/{id}', [UserController::class, 'show']);
    Route::put('/{id}/follow', [UserController::class, 'follow']);
    Route::delete('/{id}/unfollow', [UserController::class, 'unfollow']);
    Route::get('/{id}/followings', [UserController::class, 'followings']);
    Route::get('/{id}/followers', [UserController::class, 'followers']);
});
