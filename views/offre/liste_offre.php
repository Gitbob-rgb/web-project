<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des offres de stage - Plateforme de Stages</title>
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
        
        .btn-apply {
            background-color: #8BC34A;
        }
        
        .btn-wishlist {
            background-color: #FF9800;
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
            <h2>Liste des offres de stage</h2>
            <div>
                <a href="index.php?page=dashboard" class="btn">Tableau de bord</a>
                <?php if (isset($_SESSION['user_role']) && (strtolower($_SESSION['user_role']) === 'etudiant' || $_SESSION['user_role'] === 'admin')): ?>
                <a href="index.php?page=wishlist" class="btn">Ma Wishlist</a>
                <?php endif; ?>
                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                <a href="index.php?page=ajouter_offre" class="btn btn-add">Ajouter une offre</a>
                <?php endif; ?>
            </div>
        </div>
        
        <!-- Debug information -->
        <?php if (isset($_SESSION['user_role'])): ?>
        <div style="background: #f0f0f0; padding: 10px; margin-bottom: 10px; border-radius: 4px;">
            Rôle actuel: <?php echo htmlspecialchars($_SESSION['user_role']); ?>
        </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                    switch ($_GET['success']) {
                        case 'create':
                            echo 'L\'offre a été ajoutée avec succès.';
                            break;
                        case 'update':
                            echo 'L\'offre a été mise à jour avec succès.';
                            break;
                        case 'delete':
                            echo 'L\'offre a été supprimée avec succès.';
                            break;
                        case 'added_to_wishlist':
                            echo 'L\'offre a été ajoutée à votre wishlist avec succès.';
                            break;
                        case 'apply':
                            echo 'Votre candidature a été soumise avec succès.';
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
                            echo 'Une erreur est survenue lors de la suppression de l\'offre.';
                            break;
                        case 'offre_not_found':
                            echo 'L\'offre demandée n\'existe pas.';
                            break;
                        case 'permission_denied':
                            echo 'Vous n\'avez pas l\'autorisation d\'effectuer cette action.';
                            break;
                        case 'already_in_wishlist':
                            echo 'Cette offre est déjà dans votre wishlist.';
                            break;
                        case 'add_to_wishlist_failed':
                            echo 'Une erreur est survenue lors de l\'ajout à votre wishlist.';
                            break;
                        case 'already_applied':
                            echo 'Vous avez déjà postulé à cette offre.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php" method="GET" class="search-container">
            <input type="hidden" name="page" value="liste_offre">
            <input type="text" name="search" placeholder="Rechercher une offre..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Rechercher</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Titre</th>
                    <th>Entreprise</th>
                    <th>Spécialité</th>
                    <th>Date début</th>
                    <th>Date fin</th>
                    <th>Candidatures</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($offres)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Aucune offre trouvée</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($offres as $offre): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($offre['titre']); ?></td>
                            <td><?php echo htmlspecialchars($offre['entreprise_nom']); ?></td>
                            <td><?php echo htmlspecialchars(isset($offre['specialite']) && $offre['specialite'] ? $offre['specialite'] : 'Non précisé'); ?></td>
                            <td><?php echo isset($offre['date_debut']) && $offre['date_debut'] ? date('d/m/Y', strtotime($offre['date_debut'])) : 'Non précisé'; ?></td>
                            <td><?php echo isset($offre['date_fin']) && $offre['date_fin'] ? date('d/m/Y', strtotime($offre['date_fin'])) : 'Non précisé'; ?></td>
                            <td><?php echo $offre['candidature_count']; ?></td>
                            <td class="action-buttons">
                                <a href="index.php?page=postuler_offre&id=<?php echo $offre['id']; ?>" class="btn btn-apply">Postuler</a>
                                
                                <?php if (isset($_SESSION['user_role']) && (strtolower($_SESSION['user_role']) === 'etudiant' || $_SESSION['user_role'] === 'admin')): ?>
                                <a href="index.php?page=ajouter_wishlist&id=<?php echo $offre['id']; ?>" class="btn btn-wishlist">Ajouter à la wishlist</a>
                                <?php endif; ?>
                                
                                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                                    <a href="index.php?page=modifier_offre&id=<?php echo $offre['id']; ?>" class="btn btn-edit">Modifier</a>
                                    <a href="index.php?page=supprimer_offre&id=<?php echo $offre['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">Supprimer</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination (à implémenter) -->
        <div class="pagination">
            <a href="#">&laquo;</a>
            <a href="#" class="active">1</a>
            <a href="#">2</a>
            <a href="#">3</a>
            <a href="#">&raquo;</a>
        </div>
    </div>
</body>
</html> 