<?php

namespace App\Http\Controllers;

use App\Models\Article;
use Illuminate\Http\Request;
use App\Services\ArticleFetcher;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
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
        $today = Carbon::today()->toDateString();
        foreach ($articles as $articleData) {

            
            //Validate data
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


            if (isset($articleData['published_at']) && Carbon::parse($articleData['published_at'])->toDateString() === $today) {
                Article::updateOrCreate(
                    ['title' => $articleData['title']], // Clé unique
                    [
                        'author' => $articleData['author'],
                        'content' => $articleData['content'],
                        'published_at' => $articleData['published_at'],
                        'category' => $articleData['category'],
                        'source' => 'Le Monde',
                    ]
                );
                
            }
        }

        return response()->json(['message' => 'Articles Le Monde récupérés et stockés !']);
    }

    public function getLequipeArticles()
    {
        $articles = $this->fetcher->fetchLequipeArticles()['data'];

        $today = Carbon::today()->toDateString();
        foreach ($articles as $articleData) {
            
            //Validate data
            $validator = Validator::make($articleData, [
                'title' => 'required|string|max:255',
                'author.first_name' => 'required|string|max:100', // Validation du prénom de l'auteur
                'author.last_name' => 'required|string|max:100',  // Validation du nom de famille de l'auteur
                'content' => 'required|string',
                'created_at' => 'required|date', // Utiliser created_at comme published_at
                'category.name' => 'required|string|max:100', // Validation du nom de la catégorie
            ]);
    
            if ($validator->fails()) {
                // Log and skip invalid data
                Log::warning('Invalid article data', $articleData);
                continue;
            }


            if (isset($articleData['created_at']) && Carbon::parse($articleData['created_at'])->toDateString() === $today) {
                Article::updateOrCreate(
                    ['title' => $articleData['title']], // Utiliser le titre comme clé unique
                    [
                        'author' => $articleData['author']['first_name'] . ' ' . $articleData['author']['last_name'],
                        'content' => $articleData['content'],
                        'published_at' => Carbon::parse($articleData['created_at'])->format('Y-m-d H:i:s'),
                        'category' => $articleData['category']['name'],
                        'source' => 'L\'Équipe',
                    ]
                );
                
            }
        }

        return response()->json(['message' => 'Articles Lequipe récupérés et stockés !']);
    }

    public function getLeParisienArticles()
    {
        $articles = $this->fetcher->fetchLeParisienArticles()['data'];

        $today = Carbon::today()->toDateString();
        foreach ($articles as $articleData) {
            
            //Validate data
            $validator = Validator::make($articleData, [
                'headlines.basic' => 'required|string|max:255', // Titre de l'article
                'credits' => 'nullable|array',                 // Crédits (auteur)
                'credits.*.name' => 'nullable|string|max:255', // Nom de l'auteur
                'content' => 'required|string',                // Contenu de l'article
                'publish_date' => 'required|integer',          // Date de publication sous forme de timestamp
                'keywords' => 'nullable|array',                // Mots-clés (catégories)
                'keywords.*' => 'string|max:100',              // Chaque mot-clé
            ]);
    
            if ($validator->fails()) {
                // Log and skip invalid data
                Log::warning('Invalid article data', $articleData);
                continue;
            }

            $publishedAt = Carbon::createFromTimestamp($articleData['publish_date'])->toDateString();
            if ($publishedAt === $today) {
                $authorName = isset($articleData['credits'][0]['name']) ? $articleData['credits'][0]['name'] : 'Unknown';
    
                Article::updateOrCreate(
                    ['title' => $articleData['headlines']['basic']], // Utiliser le titre comme clé unique
                    [
                        'author' => $authorName,
                        'content' => $articleData['content'],
                        'published_at' => Carbon::createFromTimestamp($articleData['publish_date'])->format('Y-m-d H:i:s'),
                        'category' => implode(', ', $articleData['keywords']), // Concaténer les mots-clés en une chaîne
                        'source' => 'Le Parisien',
                    ]
                );
            }
        }

        return response()->json(['message' => 'Articles Le Parisien récupérés et stockés !']);
    }

    public function index()
    {
        $articles = Article::all();
        return response()->json($articles);
    }

    public function showArticles(Request $request)
    {
   
        $query = Article::query();

        if ($request->filled('category')) {
            $query->where('category', 'like', '%' . $request->category . '%');
        }

        if ($request->filled('source')) {
            $query->where('source', 'like', '%' . $request->source . '%');
        }

        if ($request->filled('published_at')) {
            $query->whereDate('published_at', $request->published_at);
        }

        if ($request->filled('author')) {
            $query->where('author', 'LIKE', '%' . $request->input('author') . '%');
        }

        $articles = $query->orderBy('published_at', 'desc')->get();

        return view('articles.index', compact('articles'));
    }

}

