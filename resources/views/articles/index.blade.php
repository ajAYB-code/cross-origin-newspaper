<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Articles</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-4">
        <h1 class="mb-4">Liste des Articles</h1>

        <!-- Formulaire de filtre -->
        <form method="GET" action="{{ route('articles') }}" class="mb-4">
            <div class="row">
                <!-- Champ de filtre par catégorie -->
                <div class="col-md-3 mb-3">
                    <input type="text" name="category" class="form-control" placeholder="Catégorie" value="{{ request('category') }}">
                </div>
                
                <!-- Champ de filtre par source (journal) -->
                <div class="col-md-3 mb-3">
                    <input type="text" name="source" class="form-control" placeholder="Journal" value="{{ request('source') }}">
                </div>
                
                <!-- Champ de filtre par date -->
                <div class="col-md-3 mb-3">
                    <input type="date" name="published_at" class="form-control" value="{{ request('published_at') }}">
                </div>
                
                <!-- Bouton de soumission -->
                <div class="col-md-3 mb-3">
                    <button type="submit" class="btn btn-primary w-100">Filtrer</button>
                </div>
            </div>
        </form>

        <!-- Liste des articles -->
        @if($articles->isEmpty())
            <div class="alert alert-warning">Aucun article trouvé.</div>
        @else
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Titre</th>
                        <th>Auteur</th>
                        <th>Catégorie</th>
                        <th>Journal</th>
                        <th>Date de Publication</th>
                        <th>Contenu</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($articles as $article)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $article->title }}</td>
                            <td>{{ $article->author }}</td>
                            <td>{{ $article->category }}</td>
                            <td>{{ $article->source }}</td>
                            <td>{{ $article->published_at }}</td>
                            <td>{{ Str::limit($article->content, 50) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif


    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
