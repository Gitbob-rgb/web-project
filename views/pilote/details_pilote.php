<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails du Pilote - Plateforme de Stages</title>
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
        
        .btn-primary {
            background-color: #2196F3;
        }
        
        .btn-danger {
            background-color: #dc3545;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .info-section {
            margin-bottom: 2rem;
        }
        
        .info-section h3 {
            border-bottom: 1px solid #eee;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 1rem;
        }
        
        .info-label {
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        
        table th, table td {
            padding: 0.75rem;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        table th {
            background-color: #f5f5f5;
        }
        
        .no-data {
            font-style: italic;
            color: #777;
            margin: 1rem 0;
        }
        
        .actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 2rem;
        }

        .badge {
            display: inline-block;
            padding: 0.25rem 0.5rem;
            border-radius: 10px;
            font-size: 0.8rem;
            color: white;
        }
        
        .badge-primary {
            background-color: #2196F3;
        }
        
        .badge-success {
            background-color: #4CAF50;
        }
        
        .badge-warning {
            background-color: #FF9800;
        }
        
        .badge-danger {
            background-color: #F44336;
        }
        
        .rating {
            color: #FF9800;
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Détails du Pilote</h2>
            <div>
                <a href="index.php?page=dashboard" class="btn">Tableau de bord</a>
                <a href="index.php?page=liste_pilote" class="btn">Liste des pilotes</a>
            </div>
        </div>
        
        <?php if (isset($success_message)): ?>
            <div class="alert alert-success"><?php echo $success_message; ?></div>
        <?php endif; ?>
        
        <?php if (isset($pilote)): ?>
            <div class="info-section">
                <h3>Informations</h3>
                <div class="info-grid">
                    <div class="info-label">Email:</div>
                    <div><?php echo htmlspecialchars($pilote['email']); ?></div>
                </div>
            </div>
            
            <div class="info-section">
                <h3>Candidatures gérées</h3>
                <?php if (!empty($candidatures)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Offre</th>
                                <th>Entreprise</th>
                                <th>Étudiant</th>
                                <th>Date</th>
                                <th>Statut</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($candidatures as $candidature): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($candidature['titre']); ?></td>
                                    <td><?php echo htmlspecialchars($candidature['nom_entreprise']); ?></td>
                                    <td><?php echo htmlspecialchars($candidature['email_etudiant']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($candidature['date_candidature'])); ?></td>
                                    <td>
                                        <?php 
                                        $status_class = '';
                                        switch($candidature['statut']) {
                                            case 'en attente': $status_class = 'badge-warning'; break;
                                            case 'acceptée': $status_class = 'badge-success'; break;
                                            case 'refusée': $status_class = 'badge-danger'; break;
                                            default: $status_class = 'badge-primary';
                                        }
                                        ?>
                                        <span class="badge <?php echo $status_class; ?>"><?php echo htmlspecialchars($candidature['statut']); ?></span>
                                    </td>
                                    <td>
                                        <a href="index.php?page=voir_offre&id=<?php echo $candidature['offre_id']; ?>" class="btn btn-primary">Voir l'offre</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Aucune candidature gérée par ce pilote.</p>
                <?php endif; ?>
            </div>
            
            <div class="info-section">
                <h3>Entreprises notées</h3>
                <?php if (!empty($notations)): ?>
                    <table>
                        <thead>
                            <tr>
                                <th>Entreprise</th>
                                <th>Note</th>
                                <th>Commentaire</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($notations as $notation): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($notation['nom_entreprise']); ?></td>
                                    <td>
                                        <div class="rating">
                                            <?php 
                                            for ($i = 1; $i <= 5; $i++) {
                                                echo $i <= $notation['note'] ? '★' : '☆';
                                            }
                                            ?>
                                        </div>
                                    </td>
                                    <td><?php echo htmlspecialchars($notation['commentaire']); ?></td>
                                    <td><?php echo date('d/m/Y', strtotime($notation['date_notation'])); ?></td>
                                    <td>
                                        <a href="index.php?page=voir_entreprise&id=<?php echo $notation['entreprise_id']; ?>" class="btn btn-primary">Voir l'entreprise</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="no-data">Aucune entreprise notée par ce pilote.</p>
                <?php endif; ?>
            </div>
            
            <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                <div class="actions">
                    <a href="index.php?page=modifier_pilote&id=<?php echo $pilote['id']; ?>" class="btn btn-primary">Modifier</a>
                    <a href="index.php?page=supprimer_pilote&id=<?php echo $pilote['id']; ?>" class="btn btn-danger" onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce pilote ?')">Supprimer</a>
                    <a href="index.php?page=reset_password_pilote&id=<?php echo $pilote['id']; ?>" class="btn" onclick="return confirm('Êtes-vous sûr de vouloir réinitialiser le mot de passe de ce pilote ?')">Réinitialiser le mot de passe</a>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <div class="alert alert-danger">Pilote non trouvé.</div>
        <?php endif; ?>
    </div>
</body>
</html> 