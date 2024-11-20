<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Articles</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
        }
        th {
            background-color: #f4f4f4;
        }
    </style>
</head>
<body>
    <h1>Liste des Articles</h1>
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Auteur</th>
                <th>Contenu</th>
                <th>Date de Publication</th>
                <th>Catégorie</th>
                <th>Source</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($articles as $article)
                <tr>
                    <td>{{ $article->title }}</td>
                    <td>{{ $article->author }}</td>
                    <td>{{ Str::limit($article->content, 50) }}</td>
                    <td>{{ $article->published_at }}</td>
                    <td>{{ $article->category }}</td>
                    <td>{{ $article->source }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6">Aucun article disponible.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
