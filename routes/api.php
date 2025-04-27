<?php

use App\Http\Controllers\AuthorController;
use App\Http\Controllers\TopicController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::resource('authors', AuthorController::class)->names('authors');
Route::resource('topics', TopicController::class)->names('topics');