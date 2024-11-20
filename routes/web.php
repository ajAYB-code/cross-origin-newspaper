<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ArticleController;
use App\Http\Controllers\CommentController;

Route::get('/', function () {
    return view('welcome');
});
// Route::get('/lemonde/articles', [ArticleController::class, 'getLeMondeArticles']);
// Route::get('/lequipe/articles', [ArticleController::class, 'getLequipeArticles']);
// Route::get('/leparisien/articles', [ArticleController::class, 'getLeParisienArticles']);
Route::get('/articles', [ArticleController::class, 'showArticles'])->name(("articles"));
Route::get('/articles/{id}', [ArticleController::class, 'showArticle'])->name('articles.show');
Route::post('/articles/{article}/comments', [CommentController::class, 'store'])->name('comments.store');


