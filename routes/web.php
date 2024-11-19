<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/fetch-lemonde', [ArticleController::class, 'getLeMondeArticles']);

