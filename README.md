# Application de Gestion de Stages

Une application PHP de gestion de stages qui suit l'architecture MVC (Modèle-Vue-Contrôleur).

## Structure du projet

```
├── controllers/              # Contrôleurs
│   ├── AuthController.php    # Contrôleur d'authentification
│   ├── EntrepriseController.php # Gestion des entreprises
│   ├── EtudiantController.php   # Gestion des étudiants
│   ├── OffreController.php      # Gestion des offres de stage
│   └── PiloteController.php     # Gestion des pilotes
├── models/                   # Modèles
│   ├── Database.php          # Connexion à la base de données
│   ├── UserModel.php         # Modèle utilisateur
│   ├── EntrepriseModel.php   # Gestion des données d'entreprises
│   ├── EtudiantModel.php     # Gestion des données d'étudiants
│   ├── OffreModel.php        # Gestion des données d'offres de stage
│   └── PiloteModel.php       # Gestion des données de pilotes
├── views/                    # Vues
│   ├── login.php             # Page de connexion
│   ├── dashboard.php         # Tableau de bord principal
│   ├── dashboard_etudiant.php # Tableau de bord étudiant
│   ├── dashboard_pilote.php  # Tableau de bord pilote
│   ├── entreprise/           # Vues pour les entreprises
│   │   ├── liste_entreprise.php
│   │   ├── form_entreprise.php
│   │   └── details_entreprise.php
│   ├── etudiant/             # Vues pour les étudiants
│   │   ├── liste_etudiant.php
│   │   ├── form_etudiant.php
│   │   └── details_etudiant.php
│   ├── offre/                # Vues pour les offres de stage
│   │   ├── liste_offre.php
│   │   ├── form_offre.php
│   │   └── details_offre.php
│   └── pilote/               # Vues pour les pilotes
│       ├── liste_pilote.php
│       ├── form_pilote.php
│       └── details_pilote.php
├── public/                   # Ressources publiques
│   └── css/                  # Feuilles de style
│       └── style.css         # Style principal
├── index.php                 # Point d'entrée de l'application
└── README.md                 # Documentation
```

## Architecture MVC

Cette application suit le modèle MVC (Modèle-Vue-Contrôleur) :

- **Modèle** : Représente les données et la logique métier (UserModel.php, EtudiantModel.php, etc.)
- **Vue** : Affiche l'interface utilisateur (vues dans le dossier views/)
- **Contrôleur** : Gère les entrées de l'utilisateur et fait le lien entre le modèle et la vue (contrôleurs dans le dossier controllers/)

## Fonctionnalités principales

- Authentification à plusieurs niveaux (admin, pilote, étudiant)
- Tableaux de bord adaptés selon le rôle de l'utilisateur
- Gestion des entreprises partenaires
- Gestion des offres de stage
- Gestion des étudiants
- Gestion des pilotes
- Système de wishlist pour les offres de stage

## Comment utiliser

1. Placez tous les fichiers sur un serveur PHP (comme XAMPP, WAMP, ou un serveur web avec PHP).
2. Importez la structure de base de données fournie.
3. Accédez à l'application via votre navigateur (`http://localhost/votre-dossier`).
4. Connectez-vous avec les identifiants fournis selon votre rôle.

## Améliorations possibles

- Implémenter un système de pagination pour les listes
- Ajouter un système de notifications
- Améliorer l'interface mobile
- Renforcer la sécurité (double authentification, etc.)
- Ajouter des statistiques détaillées sur les stages et ca  ndidatures 