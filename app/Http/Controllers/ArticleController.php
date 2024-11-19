<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\ArticleFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class ArticleController extends Controller
{

    protected $fetcher;

    public function __construct(ArticleFetcher $fetcher)
    {
        $this->fetcher = $fetcher;
    }

    public function getLeMondeArticles()
    {
        $articles = $this->fetcher->fetchLeMondeArticles()['data'];

        //Validate data
        foreach ($articles as $articleData) {
            $validator = Validator::make($articleData, [
                'title' => 'required|string|max:255',
                'author' => 'nullable|string|max:255',
                'content' => 'required|string',
                'published_at' => 'nullable|date',
                'category' => 'nullable|string|max:100',
            ]);
    
            if ($validator->fails()) {
                // Log and skip invalid data
                Log::warning('Invalid article data', $articleData);
                continue;
            }
    
            Article::updateOrCreate(
                ['title' => $articleData['title']], // Clé unique
                [
                    'author' => $articleData['author'] ?? 'Inconnu',
                    'content' => $articleData['content'] ?? '',
                    'published_at' => $articleData['published_at'] ?? null,
                    'category' => $articleData['category'] ?? 'Non catégorisé',
                    'source' => 'Le Monde',
                ]
            );
        }

        return response()->json(['message' => 'Articles Le Monde récupérés et stockés !']);
    }

    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    public function showArticles()
    {
        $articles = Article::all();
        return view('articles.index', compact('articles'));
    }

}

