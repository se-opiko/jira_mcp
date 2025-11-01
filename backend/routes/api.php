<?php

use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\TagController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

Route::apiResource('articles', ArticleController::class);
Route::get('tags', [TagController::class, 'index']);
Route::get('categories', [CategoryController::class, 'index']);
