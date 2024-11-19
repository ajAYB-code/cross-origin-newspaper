<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/lemonde/articles', [ArticleController::class, 'getLeMondeArticles']);
Route::get('/lequipe/articles', [ArticleController::class, 'getLequipeArticles']);

