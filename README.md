# Application de Connexion MVC

Une application simple de connexion PHP qui suit l'architecture MVC (Modèle-Vue-Contrôleur).

## Structure du projet

```
├── controllers/           # Contrôleurs
│   └── AuthController.php # Contrôleur d'authentification
├── models/                # Modèles
│   └── UserModel.php      # Modèle utilisateur
├── views/                 # Vues
│   ├── login.php          # Page de connexion
│   └── dashboard.php      # Tableau de bord après connexion
├── public/                # Ressources publiques
│   └── css/               # Feuilles de style
│       └── style.css      # Style principal
├── index.php              # Point d'entrée de l'application
└── README.md              # Documentation
```

## Architecture MVC

Cette application suit le modèle MVC (Modèle-Vue-Contrôleur) :

- **Modèle** : Représente les données et la logique métier (UserModel.php)
- **Vue** : Affiche l'interface utilisateur (login.php, dashboard.php)
- **Contrôleur** : Gère les entrées de l'utilisateur et fait le lien entre le modèle et la vue (AuthController.php)

## Comment utiliser

1. Placez tous les fichiers sur un serveur PHP (comme XAMPP, WAMP, ou un serveur web avec PHP).
2. Accédez à l'application via votre navigateur (`http://localhost/votre-dossier`).
3. Utilisez les identifiants suivants pour vous connecter :
   - Utilisateur: `admin` / Mot de passe: `password123`
   - Utilisateur: `user` / Mot de passe: `user123`

## Améliorations possibles

- Ajouter une base de données pour stocker les utilisateurs
- Implémenter un hachage de mot de passe sécurisé
- Ajouter une fonctionnalité d'inscription
- Améliorer la validation des formulaires
- Mettre en place un système de gestion des erreurs plus robuste 