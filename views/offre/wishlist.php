<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ma Wishlist - Plateforme de Stages</title>
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
        
        .btn {
            background-color: #607D8B;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
            margin-left: 0.5rem;
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
        
        .btn-apply {
            background-color: #8BC34A;
        }
        
        .btn-delete {
            background-color: #F44336;
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
        
        .empty-wishlist {
            text-align: center;
            padding: 2rem;
            color: #666;
        }
        
        .empty-wishlist p {
            margin-bottom: 1rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Ma Wishlist</h2>
            <div>
                <a href="index.php?page=dashboard" class="btn">Tableau de bord</a>
                <a href="index.php?page=liste_offre" class="btn">Liste des offres</a>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                    switch ($_GET['success']) {
                        case 'remove':
                            echo 'L\'offre a été retirée de votre wishlist avec succès.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                    switch ($_GET['error']) {
                        case 'offre_not_found':
                            echo 'L\'offre demandée n\'existe pas.';
                            break;
                        case 'permission_denied':
                            echo 'Vous n\'avez pas l\'autorisation d\'effectuer cette action.';
                            break;
                        case 'remove_failed':
                            echo 'Une erreur est survenue lors de la suppression de l\'offre de votre wishlist.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($wishlist)): ?>
            <div class="empty-wishlist">
                <p>Votre wishlist est vide.</p>
                <a href="index.php?page=liste_offre" class="btn btn-info">Parcourir les offres</a>
            </div>
        <?php else: ?>
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Entreprise</th>
                        <th>Spécialité</th>
                        <th>Date début</th>
                        <th>Date fin</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($wishlist as $offre): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($offre['titre']); ?></td>
                            <td><?php echo htmlspecialchars($offre['entreprise_nom']); ?></td>
                            <td><?php echo htmlspecialchars(isset($offre['specialite']) && $offre['specialite'] ? $offre['specialite'] : 'Non précisé'); ?></td>
                            <td><?php echo isset($offre['date_debut']) && $offre['date_debut'] ? date('d/m/Y', strtotime($offre['date_debut'])) : 'Non précisé'; ?></td>
                            <td><?php echo isset($offre['date_fin']) && $offre['date_fin'] ? date('d/m/Y', strtotime($offre['date_fin'])) : 'Non précisé'; ?></td>
                            <td class="action-buttons">
                                <a href="index.php?page=voir_offre&id=<?php echo $offre['offre_id']; ?>" class="btn btn-info">Voir</a>
                                <a href="index.php?page=postuler_offre&id=<?php echo $offre['offre_id']; ?>" class="btn btn-apply">Postuler</a>
                                <a href="index.php?page=supprimer_wishlist&id=<?php echo $offre['offre_id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir retirer cette offre de votre wishlist ?')">Supprimer</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
</body>
</html> 