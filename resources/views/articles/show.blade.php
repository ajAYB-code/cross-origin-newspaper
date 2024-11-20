@extends('layout')

@section('content')
<div class="container">
    <h1>{{ $article->title }}</h1>
    <p><strong>Author:</strong> {{ $article->author }}</p>
    <p><strong>Published At:</strong> {{ $article->published_at }}</p>
    <p><strong>Source:</strong> {{ $article->source }}</p>
    <p><strong>Category:</strong> {{ $article->category }}</p>
    <p>{{ $article->content }}</p>

    <hr>

    <h3>Commentaires</h3>
    @if ($article->comments->isEmpty())
        <p>Aucun commentaire pour cet article.</p>
    @else
        @foreach ($article->comments as $comment)
            <div class="comment mb-3">
                <p><strong>{{ htmlspecialchars($comment->author) }}</strong> - {{ $comment->created_at->format('d M Y H:i') }}</p>
                <p>{{ htmlspecialchars($comment->content) }}</p>
            </div>
        @endforeach
    @endif

    <hr>

    <h4>Ajouter un Commentaire</h4>
    <form action="{{ route('comments.store', $article->id) }}" method="POST">
        @csrf
        <div class="form-group">
            <label for="author">Nom</label>
            <input type="text" class="form-control" name="author" id="author" required>
        </div>
        <div class="form-group">
            <label for="content">Commentaire</label>
            <textarea name="content" id="content" class="form-control" rows="4" required></textarea>
        </div>
        <button type="submit" class="btn btn-success mt-2">Ajouter</button>
    </form>
</div>
@endsection
