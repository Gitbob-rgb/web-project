<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion des Étudiants - Plateforme de Stages</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }
        
        .search-container {
            margin-bottom: 1rem;
            display: flex;
        }
        
        .search-container input {
            flex-grow: 1;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px 0 0 4px;
        }
        
        .search-container button {
            padding: 0.5rem 1rem;
            border-radius: 0 4px 4px 0;
            border: 1px solid #2196F3;
            background-color: #2196F3;
            color: white;
            cursor: pointer;
        }
        
        .btn {
            background-color: #607D8B;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-left: 0.5rem;
        }
        
        .btn-add {
            background-color: #4CAF50;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        th, td {
            padding: 0.8rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        tr:hover {
            background-color: #f5f5f5;
        }
        
        .action-buttons {
            display: flex;
            gap: 5px;
        }
        
        .action-buttons .btn {
            margin: 0;
            padding: 0.3rem 0.6rem;
            font-size: 0.9rem;
        }
        
        .btn-info {
            background-color: #2196F3;
        }
        
        .btn-edit {
            background-color: #FF9800;
        }
        
        .btn-delete {
            background-color: #F44336;
        }
        
        .btn-reset {
            background-color: #9C27B0;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .alert-success {
            background-color: #d4edda;
            border-color: #c3e6cb;
            color: #155724;
        }
        
        .alert-danger {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            margin-top: 1rem;
        }
        
        .pagination a {
            padding: 0.5rem 0.8rem;
            margin: 0 0.2rem;
            border: 1px solid #ddd;
            color: #2196F3;
            text-decoration: none;
            border-radius: 4px;
        }
        
        .pagination a.active {
            background-color: #2196F3;
            color: white;
            border-color: #2196F3;
        }
        
        .pagination a:hover:not(.active) {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Gestion des Étudiants</h2>
            <div>
                <a href="index.php?page=dashboard" class="btn">Tableau de bord</a>
                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                <a href="index.php?page=ajouter_etudiant" class="btn btn-add">Ajouter un étudiant</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                    switch ($_GET['success']) {
                        case 'create':
                            echo 'L\'étudiant a été ajouté avec succès.';
                            break;
                        case 'update':
                            echo 'L\'étudiant a été mis à jour avec succès.';
                            break;
                        case 'delete':
                            echo 'L\'étudiant a été supprimé avec succès.';
                            break;
                        case 'reset':
                            if (isset($_GET['password'])) {
                                echo 'Le mot de passe a été réinitialisé avec succès. Nouveau mot de passe : <strong>' . htmlspecialchars($_GET['password']) . '</strong>';
                            } else {
                                echo 'Le mot de passe a été réinitialisé avec succès.';
                            }
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                    switch ($_GET['error']) {
                        case 'delete':
                            echo 'Une erreur est survenue lors de la suppression de l\'étudiant. Assurez-vous qu\'il n\'a pas de candidatures en cours.';
                            break;
                        case 'etudiant_not_found':
                            echo 'L\'étudiant demandé n\'existe pas.';
                            break;
                        case 'permission_denied':
                            echo 'Vous n\'avez pas l\'autorisation de modifier ou supprimer des étudiants.';
                            break;
                        case 'reset':
                            echo 'Une erreur est survenue lors de la réinitialisation du mot de passe.';
                            break;
                        case 'reset_error':
                            echo 'Une erreur système est survenue pendant la réinitialisation du mot de passe. Veuillez contacter l\'administrateur.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php" method="GET" class="search-container">
            <input type="hidden" name="page" value="liste_etudiant">
            <input type="text" name="search" placeholder="Rechercher un étudiant..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Rechercher</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Email</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($etudiants)): ?>
                    <tr>
                        <td colspan="2" style="text-align: center;">Aucun étudiant trouvé</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($etudiants as $etudiant): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($etudiant['email']); ?></td>
                            <td class="action-buttons">
                                <a href="index.php?page=voir_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-info">Détails</a>
                                
                                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                                    <a href="index.php?page=modifier_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-edit">Modifier</a>
                                    <a href="index.php?page=supprimer_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet étudiant ?')">Supprimer</a>
                                    <a href="index.php?page=reset_password_etudiant&id=<?php echo $etudiant['id']; ?>" class="btn btn-reset" onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de cet étudiant ?')">Réinitialiser MDP</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</body>
</html> 