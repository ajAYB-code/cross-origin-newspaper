@extends('layout')
@section('content')
    <div class="container mt-4">
        <h1 class="mb-4">Liste des Articles</h1>

        <form method="GET" action="{{ route('articles') }}" class="mb-4">
            <div class="row">
                <div class="col-md-3 mb-3">
                    <input type="text" name="category" class="form-control" placeholder="Catégorie" value="{{ request('category') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <input type="text" name="source" class="form-control" placeholder="Journal" value="{{ request('source') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <input type="date" name="published_at" class="form-control" value="{{ request('published_at') }}">
                </div>
                <div class="form-group col-md-3">
                    <input type="text" class="form-control" name="search" placeholder="Recherche par mots-clés" value="{{ request('search') }}">
                </div>
                <div class="col-md-3 mb-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- Liste des articles -->
        <div class="row">
        @forelse($articles as $article)
            <div class="col-md-4 mb-4">
                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title">{{ $article->title }}</h5>
                        <p class="card-text">
                            <strong>Publié le:</strong> {{ \Carbon\Carbon::parse($article->published_at)->format('d/m/Y') }}
                        </p>
                        <p class="card-text"><strong>Source:</strong> {{ $article->source }}</p>
                        <p class="card-text"><strong>Category:</strong> {{ $article->category }}</p>
                        <p class="card-text text-truncate" style="max-height: 4.5em; overflow: hidden;">
                        {{ $article->content }}
                        </p>
                        <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary">Voir Détails</a>
                    </div>
                </div>
            </div>
        @empty
            <p class="text-center">Aucun article trouvé.</p>
        @endforelse
        </div>

    </div>
@endsection
