<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;


class CommentController extends Controller
{

    public function store(Request $request, $articleId)
    {
        // Validate the comment
        $request->validate([
            'content' => 'required|string|max:2000',
        ]);
        // Sanitize content
        $sanitizedContent = Str::of($request->input('content'))->trim()->stripTags();
        $article = Article::findOrFail($articleId);

        $article->comments()->create([
            'content' => $sanitizedContent,
            'user_id' => Auth::id(),
        ]);

        return redirect()->route('articles.show', $article->id)
                         ->with('success', 'Commentaire ajouté avec succès.');
    }
}
