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
                Log::warning('Invalid article data', $articleData);
                continue;
            }


            if (isset($articleData['published_at']) && Carbon::parse($articleData['published_at'])->toDateString() === $today) {
                Article::updateOrCreate(
                    ['title' => $articleData['title']],
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
                'author.first_name' => 'required|string|max:100',
                'author.last_name' => 'required|string|max:100', 
                'content' => 'required|string',
                'created_at' => 'required|date',
                'category.name' => 'required|string|max:100',
            ]);
    
            if ($validator->fails()) {
                Log::warning('Invalid article data', $articleData);
                continue;
            }


            if (isset($articleData['created_at']) && Carbon::parse($articleData['created_at'])->toDateString() === $today) {
                Article::updateOrCreate(
                    ['title' => $articleData['title']],
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
                'headlines.basic' => 'required|string|max:255',
                'credits' => 'nullable|array',                 
                'credits.*.name' => 'nullable|string|max:255',
                'content' => 'required|string',                
                'publish_date' => 'required|integer',          
                'keywords' => 'nullable|array',                
                'keywords.*' => 'string|max:100',              
            ]);
    
            if ($validator->fails()) {
                Log::warning('Invalid article data', $articleData);
                continue;
            }

            $publishedAt = Carbon::createFromTimestamp($articleData['publish_date'])->toDateString();
            if ($publishedAt === $today) {
                $authorName = isset($articleData['credits'][0]['name']) ? $articleData['credits'][0]['name'] : 'Unknown';
    
                Article::updateOrCreate(
                    ['title' => $articleData['headlines']['basic']],
                    [
                        'author' => $authorName,
                        'content' => $articleData['content'],
                        'published_at' => Carbon::createFromTimestamp($articleData['publish_date'])->format('Y-m-d H:i:s'),
                        'category' => implode(', ', $articleData['keywords']), 
                        'source' => 'Le Parisien',
                    ]
                );
            }
        }

        return response()->json(['message' => 'Articles Le Parisien récupérés et stockés !']);
    }
    public function getLiberationArticles()
    {
        $today = Carbon::today()->toDateString();
        $page = 1;
        $hasMorePages = true;
        $fetchedArticles = 0;
    
        while ($hasMorePages) {
            
            $response = $this->fetcher->fetchLiberationArticles($page, 'acs'); // 'acs' for ascending order
    
            if (empty($response['data'])) {
                // Stop if no data is returned
                $hasMorePages = false;
                break;
            }
    
            foreach ($response['data'] as $articleData) {
                // Validate data
                $validator = Validator::make($articleData, [
                    'title' => 'required|string|max:255',
                    'author' => 'nullable|string|max:255',
                    'content' => 'required|string',
                    'published_at' => 'nullable|date',
                    'category' => 'nullable|string|max:100',
                ]);
    
                if ($validator->fails()) {
                    Log::warning('Invalid article data', $articleData);
                    continue;
                }
    
                // Check if the article is published today
                if (isset($articleData['published_at']) && Carbon::parse($articleData['published_at'])->toDateString() === $today) {
                    Article::updateOrCreate(
                        ['title' => $articleData['title']],
                        [
                            'author' => $articleData['author'],
                            'content' => $articleData['content'],
                            'published_at' => $articleData['published_at'],
                            'category' => $articleData['category'],
                            'source' => 'Libération',
                        ]
                    );
                    $fetchedArticles++;
                } elseif (isset($articleData['published_at']) && Carbon::parse($articleData['published_at'])->toDateString() < $today) {
                    // Stop fetching if we encounter an article older than today
                    $hasMorePages = false;
                    break;
                }
            }
    
            $page++;
        }
    
        return response()->json([
            'message' => "Articles Libération récupérés et stockés !",
            'fetched_articles' => $fetchedArticles,
        ]);
    }
    

    public function index(Request $request)
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

        if ($request->filled('search')) {
            $searchTerms = explode(' ', $request->search);
            $query->where(function ($subQuery) use ($searchTerms) {
                foreach ($searchTerms as $term) {
                    $subQuery->orWhere('title', 'like', '%' . $term . '%')
                             ->orWhere('content', 'like', '%' . $term . '%')
                             ->orWhere('author', 'like', '%' . $term . '%');
                }
            });
        }

        $articles = $query->orderBy('published_at', 'desc')->get();

        return view('articles.index', compact('articles'));
    }

    public function show($id)
    {
        $article = Article::with(['comments' => function ($query) {
            $query->orderBy('created_at', 'desc');
        }])->findOrFail($id);
        return view('articles.show', compact('article'));
    }

}

