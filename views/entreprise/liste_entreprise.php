<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des entreprises - Plateforme de Stages</title>
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
        
        .rating {
            color: #FFC107;
            font-weight: bold;
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
            <h2>Liste des entreprises</h2>
            <div>
                <a href="index.php?page=dashboard" class="btn">Tableau de bord</a>
                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                <a href="index.php?page=ajouter_entreprise" class="btn btn-add">Ajouter une entreprise</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                    switch ($_GET['success']) {
                        case 'create':
                            echo 'L\'entreprise a été ajoutée avec succès.';
                            break;
                        case 'update':
                            echo 'L\'entreprise a été mise à jour avec succès.';
                            break;
                        case 'delete':
                            echo 'L\'entreprise a été supprimée avec succès.';
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
                            echo 'Une erreur est survenue lors de la suppression de l\'entreprise.';
                            break;
                        case 'entreprise_not_found':
                            echo 'L\'entreprise demandée n\'existe pas.';
                            break;
                        case 'permission_denied':
                            echo 'Vous n\'avez pas l\'autorisation de modifier ou supprimer des entreprises.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php" method="GET" class="search-container">
            <input type="hidden" name="page" value="liste_entreprise">
            <input type="text" name="search" placeholder="Rechercher une entreprise..." value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">
            <button type="submit">Rechercher</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Adresse</th>
                    <th>Email</th>
                    <th>Nb Offres</th>
                    <th>Nb Candidats</th>
                    <th>Évaluation</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($entreprises)): ?>
                    <tr>
                        <td colspan="7" style="text-align: center;">Aucune entreprise trouvée</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($entreprises as $entreprise): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($entreprise['nom']); ?></td>
                            <td><?php echo htmlspecialchars(isset($entreprise['adresse']) && $entreprise['adresse'] ? $entreprise['adresse'] : 'Non renseigné'); ?></td>
                            <td><?php echo htmlspecialchars(isset($entreprise['email']) && $entreprise['email'] ? $entreprise['email'] : 'Non renseigné'); ?></td>
                            <td><?php echo $entreprise['offre_count']; ?></td>
                            <td><?php echo $entreprise['candidature_count']; ?></td>
                            <td class="rating">
                                <?php
                                    $rating = round($entreprise['moyenne_notation'], 1);
                                    echo $rating > 0 ? "$rating / 5" : "Non évalué";
                                ?>
                            </td>
                            <td class="action-buttons">
                                <a href="index.php?page=voir_entreprise&id=<?php echo $entreprise['id']; ?>" class="btn btn-info">Voir</a>
                                
                                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                                    <a href="index.php?page=modifier_entreprise&id=<?php echo $entreprise['id']; ?>" class="btn btn-edit">Modifier</a>
                                    <a href="index.php?page=supprimer_entreprise&id=<?php echo $entreprise['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')">Supprimer</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
        
        <!-- Pagination (à implémenter) -->
        <div class="pagination">
            <?php if (isset($pagination) && $pagination['pages'] > 1): ?>
                <div class="pagination-info" style="text-align: center; margin-bottom: 1rem;">
                    Affichage de <?php echo ($pagination['current'] - 1) * 10 + 1 ?> à 
                    <?php echo min($pagination['current'] * 10, $pagination['total']) ?> 
                    sur <?php echo $pagination['total'] ?> entreprises
                </div>
                
                <?php if ($pagination['current'] > 1): ?>
                    <a href="index.php?page=liste_entreprise&page_num=1<?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" title="Première page">&laquo;</a>
                    <a href="index.php?page=liste_entreprise&page_num=<?php echo ($pagination['current'] - 1); ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" title="Page précédente">&#8249;</a>
                <?php endif; ?>
                
                <?php for ($i = max(1, $pagination['current'] - 2); $i <= min($pagination['pages'], $pagination['current'] + 2); $i++): ?>
                    <a href="index.php?page=liste_entreprise&page_num=<?php echo $i; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" 
                       class="<?php echo $pagination['current'] == $i ? 'active' : ''; ?>">
                        <?php echo $i; ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['current'] < $pagination['pages']): ?>
                    <a href="index.php?page=liste_entreprise&page_num=<?php echo ($pagination['current'] + 1); ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" title="Page suivante">&#8250;</a>
                    <a href="index.php?page=liste_entreprise&page_num=<?php echo $pagination['pages']; ?><?php echo isset($_GET['search']) ? '&search=' . htmlspecialchars($_GET['search']) : ''; ?>" title="Dernière page">&raquo;</a>
                <?php endif; ?>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>

<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 2rem;
        gap: 0.5rem;
    }

    .pagination a {
        padding: 0.5rem 1rem;
        border: 1px solid #ddd;
        color: #2196F3;
        text-decoration: none;
        border-radius: 4px;
        min-width: 2.5rem;
        text-align: center;
    }

    .pagination a.active {
        background-color: #2196F3;
        color: white;
        border-color: #2196F3;
    }

    .pagination a:hover:not(.active) {
        background-color: #f5f5f5;
    }

    .pagination-info {
        color: #666;
        font-size: 0.9rem;
    }
</style>