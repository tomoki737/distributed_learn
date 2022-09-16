<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeController;
use App\Http\Controllers\QuestionController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\AnswerController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [MeController::class, 'me']);
});

Route::get('/home', [QuestionController::class, 'homeIndex']);
Route::resource('/question', QuestionController::class)->except(['show']);
Route::post('/question/search', [QuestionController::class, 'search']);
Route::post('/question/download', [QuestionController::class, 'downloadQuestion']);
Route::put('/question/{question}/except', [QuestionController::class, 'exceptQuestion']);

Route::put('/question/{question}/answer', [AnswerController::class, 'answer']);
Route::get('/answer', [AnswerController::class, 'answerIndex']);
Route::get('/answer/select', [AnswerController::class, 'selectAnswerIndex']);

Route::get('/tags/{name}', [TagController::class, 'show']);
