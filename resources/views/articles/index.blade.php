@extends('layout')

@section('content')
<div class="container my-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fw-bold">Liste des Articles</h1>
        <div class="d-flex align-items-center">
            <span class="me-3">
                <i class="bi bi-person-circle"></i> Bienvenue, <strong>{{ auth()->user()->name }}</strong>
            </span>
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-danger btn-sm">Logout</button>
            </form>
        </div>
    </div>

    <form method="GET" action="{{ route('articles.index') }}" class="mb-4">
        <div class="row g-3 align-items-center">
            <div class="col-md-3">
                <input type="text" name="category" class="form-control" placeholder="Catégorie" value="{{ request('category') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="source" class="form-control" placeholder="Journal" value="{{ request('source') }}">
            </div>
            <div class="col-md-3">
                <input type="date" name="published_at" class="form-control" value="{{ request('published_at') }}">
            </div>
            <div class="col-md-3">
                <input type="text" name="search" class="form-control" placeholder="Recherche par mots-clés" value="{{ request('search') }}">
            </div>
            <div class="col-md-12 text-center mt-3">
                <button type="submit" class="btn btn-primary w-25">Filtrer</button>
            </div>
        </div>
    </form>

    @if($articles->isEmpty())
        <div class="alert alert-warning text-center">Aucun article trouvé.</div>
    @else
        <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
            @foreach($articles as $article)
                <div class="col">
                    <div class="card h-100 shadow-sm">
                        <div class="card-body">
                            <h5 class="card-title text-primary">{{ $article->title }}</h5>
                            <p class="card-text">
                                <small class="text-muted">Publié le: {{ \Carbon\Carbon::parse($article->published_at)->format('d/m/Y') }}</small>
                            </p>
                            <p class="card-text">
                                <strong>Source:</strong> {{ $article->source }} <br>
                                <strong>Category:</strong> {{ $article->category }}
                            </p>
                            <p class="card-text">
                                {{ \Illuminate\Support\Str::limit($article->content, 100) }}
                            </p>
                            <a href="{{ route('articles.show', $article->id) }}" class="btn btn-primary">Voir Détails</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
@endsection
