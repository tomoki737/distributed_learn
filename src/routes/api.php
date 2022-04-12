<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TagController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [MeController::class, 'me']);
});

Route::resource('/question', QuestionController::class);
Route::put('/question/{question}/answer', [QuestionController::class, 'answer']);
Route::get('/answer', [QuestionController::class, 'answerIndex']);
Route::get('/home', [QuestionController::class, 'homeIndex']);

Route::get('/tags/{name}', [TagController::class, 'show']);
