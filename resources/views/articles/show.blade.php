@extends('layout')

@section('content')
<div class="container my-5">
    <div class="card shadow">
        <div class="card-body">
            <h1 class="card-title text-primary">{{ $article->title }}</h1>
            <hr>
            <p><strong>Author:</strong> {{ $article->author }}</p>
            <p><strong>Published At:</strong> {{ \Carbon\Carbon::parse($article->published_at)->format('d/m/Y H:i:s') }}</p>
            <p><strong>Source:</strong> {{ $article->source }}</p>
            <p><strong>Category:</strong> {{ $article->category }}</p>
            <p class="mt-4">{{ $article->content }}</p>
        </div>
    </div>

    <div class="my-5">
        <h2>Commentaires</h2>
        @if($article->comments->isEmpty())
            <div class="alert alert-warning">Aucun commentaire pour cet article.</div>
        @else
            <ul class="list-group">
                @foreach($article->comments as $comment)
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><strong>{{ $comment->user->name }}</strong></span>
                            <span class="text-muted">{{ \Carbon\Carbon::parse($comment->created_at)->format('d M Y H:i') }}</span>
                        </div>
                        <p class="mb-0 mt-2">{{ $comment->content }}</p>
                    </li>
                @endforeach
            </ul>
        @endif
    </div>

    @if(Auth::user()->role === 'reader' || Auth::user()->role === 'admin')
    <div class="my-5">
        <h2>Ajouter un Commentaire</h2>
        <form method="POST" action="{{ route('comments.store', $article->id) }}">
            @csrf
            <div class="mb-3">
                <label for="content" class="form-label">Commentaire</label>
                <textarea id="content" name="content" rows="4" class="form-control @error('content') is-invalid @enderror" required>{{ old('content') }}</textarea>
                @error('content')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-success">Ajouter</button>
        </form>
    </div>
    @endauth
</div>
@endsection
