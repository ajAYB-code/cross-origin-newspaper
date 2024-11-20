<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;

class CommentController extends Controller
{
    public function store(Request $request, Article $article)
    {
        // Validate the comment
        $request->validate([
            'author' => 'required|string|max:255',
            'content' => 'required|string|max:2000',
        ]);
        // Sanitize content
        $sanitizedContent = Str::of($request->input('content'))->trim()->stripTags();
        // Store the comment
        $article->comments()->create([
            'author' => $request->author,
            'content' => $sanitizedContent,
        ]);

        // Redirect back with a success message
        return redirect()->route('articles.show', $article->id)
                         ->with('success', 'Commentaire ajouté avec succès.');
    }
}
