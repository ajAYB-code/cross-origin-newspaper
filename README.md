# Projet Laravel : Gestion des Articles et Commentaires

## Description
Cette application Laravel permet de récupérer, gérer et afficher des articles provenant de différentes sources. Les utilisateurs peuvent commenter les articles, avec des restrictions d'accès basées sur leurs rôles. Un système de récupération automatisée des articles est également intégré.

## Fonctionnalités

### Authentification
- Gestion des utilisateurs avec rôles : lecteur simple, lecteur, administrateur.
- Validation des entrées (email, mot de passe, etc.).

### Articles
- Récupération automatisée d'articles depuis des API tierces toutes les 5 minutes.
- Affichage des articles triés par date et possibilité de recherche par critères.

### Commentaires
- Ajout de commentaires par les utilisateurs connectés.

# Architecture de Récupération des Articles

## Description
Le système de récupération des articles suit une architecture basée sur des **Services**.

### Structure
1. **Service** : `ArticleFetcher`
   - Centralise la logique de récupération pour chaque source API.
   - Exemple : `fetchLeMondeArticles()`, `fetchLequipeArticles()`.

2. **Contrôleur** : `ArticleController`
   - Utilise le service pour récupérer les données.
   - Valide, formate et stocke uniquement les articles publiés le jour même.
