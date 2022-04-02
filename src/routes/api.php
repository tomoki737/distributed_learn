<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\MeController;
use App\Http\Controllers\QuestionController;

Route::group(['middleware' => ['auth:sanctum']], function () {
    Route::get('/me', [MeController::class, 'me']);
});

Route::resource('/question', QuestionController::class);
