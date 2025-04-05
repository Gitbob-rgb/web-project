<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($offre['titre']); ?> - Plateforme de Stages</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
        }
        
        .title-container {
            flex-grow: 1;
        }
        
        .actions {
            display: flex;
            gap: 0.5rem;
        }
        
        .btn {
            background-color: #607D8B;
            padding: 0.5rem 1rem;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            display: inline-block;
        }
        
        .btn-edit {
            background-color: #FF9800;
        }
        
        .btn-delete {
            background-color: #F44336;
        }
        
        .btn-apply {
            background-color: #4CAF50;
            margin-top: 1rem;
            display: inline-block;
            font-size: 1.1rem;
            padding: 0.7rem 1.5rem;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .info-section {
            margin-bottom: 2rem;
            background-color: #f9f9f9;
            padding: 1.5rem;
            border-radius: 5px;
        }
        
        .info-section h3 {
            color: #333;
            margin-top: 0;
            border-bottom: 1px solid #ddd;
            padding-bottom: 0.5rem;
            margin-bottom: 1rem;
        }
        
        .info-item {
            margin-bottom: 1rem;
        }
        
        .info-label {
            font-weight: bold;
            display: block;
            margin-bottom: 0.3rem;
            color: #555;
        }
        
        .info-value {
            color: #333;
        }
        
        .description {
            line-height: 1.6;
            white-space: pre-line;
        }
        
        .candidature-list {
            list-style: none;
            padding: 0;
        }
        
        .candidature-item {
            padding: 0.7rem;
            border-bottom: 1px solid #eee;
        }
        
        .candidature-item:last-child {
            border-bottom: none;
        }
        
        .candidature-date {
            color: #777;
            font-size: 0.9rem;
        }
        
        .candidature-email {
            font-weight: bold;
        }
        
        .candidature-status {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 3px;
            margin-left: 0.5rem;
            font-size: 0.8rem;
            font-weight: bold;
        }
        
        .status-pending {
            background-color: #FFC107;
            color: #333;
        }
        
        .status-accepted {
            background-color: #4CAF50;
            color: white;
        }
        
        .status-rejected {
            background-color: #F44336;
            color: white;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title-container">
                <h2><?php echo htmlspecialchars($offre['titre']); ?></h2>
                <p><strong>Entreprise:</strong> <?php echo htmlspecialchars($offre['entreprise_nom']); ?></p>
            </div>
            <div class="actions">
                <a href="index.php?page=liste_offre" class="btn">Retour à la liste</a>
                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                <a href="index.php?page=modifier_offre&id=<?php echo $offre['id']; ?>" class="btn btn-edit">Modifier</a>
                <a href="index.php?page=supprimer_offre&id=<?php echo $offre['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">Supprimer</a>
                <?php endif; ?>
            </div>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="alert alert-success">
                <?php
                    switch ($_GET['success']) {
                        case 'apply':
                            echo 'Votre candidature a été envoyée avec succès.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger">
                <?php
                    switch ($_GET['error']) {
                        case 'permission_denied':
                            echo 'Vous n\'avez pas l\'autorisation d\'effectuer cette action.';
                            break;
                        case 'already_applied':
                            echo 'Vous avez déjà postulé à cette offre.';
                            break;
                        case 'apply':
                            echo 'Une erreur est survenue lors de l\'envoi de votre candidature.';
                            break;
                    }
                ?>
            </div>
        <?php endif; ?>
        
        <div class="info-section">
            <h3>Détails de l'offre</h3>
            
            <div class="info-item">
                <span class="info-label">Description:</span>
                <div class="description info-value"><?php echo nl2br(htmlspecialchars($offre['description'])); ?></div>
            </div>
            
            <div class="info-item">
                <span class="info-label">Entreprise:</span>
                <span class="info-value">
                    <a href="index.php?page=voir_entreprise&id=<?php echo $offre['entreprise_id']; ?>"><?php echo htmlspecialchars($offre['entreprise_nom']); ?></a>
                </span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Spécialité:</span>
                <span class="info-value"><?php echo htmlspecialchars(isset($offre['specialite']) && $offre['specialite'] ? $offre['specialite'] : 'Non précisé'); ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Date de début:</span>
                <span class="info-value"><?php echo isset($offre['date_debut']) && $offre['date_debut'] ? date('d/m/Y', strtotime($offre['date_debut'])) : 'Non précisé'; ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Date de fin:</span>
                <span class="info-value"><?php echo isset($offre['date_fin']) && $offre['date_fin'] ? date('d/m/Y', strtotime($offre['date_fin'])) : 'Non précisé'; ?></span>
            </div>
            
            <div class="info-item">
                <span class="info-label">Date de publication:</span>
                <span class="info-value"><?php echo isset($offre['date_creation']) ? date('d/m/Y', strtotime($offre['date_creation'])) : 'Inconnue'; ?></span>
            </div>
        </div>
        
        <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant'): ?>
            <?php 
            $alreadyApplied = false;
            foreach ($candidatures as $candidature) {
                if ($candidature['utilisateur_id'] == $_SESSION['user_id']) {
                    $alreadyApplied = true;
                    break;
                }
            }
            ?>
            
            <?php if (!$alreadyApplied): ?>
                <div class="info-section" style="text-align: center;">
                    <h3>Intéressé(e) par cette offre?</h3>
                    <a href="index.php?page=postuler_offre&id=<?php echo $offre['id']; ?>" class="btn btn-apply">Postuler à cette offre</a>
                </div>
            <?php else: ?>
                <div class="info-section" style="text-align: center;">
                    <h3>Vous avez déjà postulé à cette offre</h3>
                    <p>Votre candidature a été enregistrée. L'entreprise vous contactera si votre profil les intéresse.</p>
                </div>
            <?php endif; ?>
        <?php endif; ?>
        
        <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote') && !empty($candidatures)): ?>
            <div class="info-section">
                <h3>Candidatures (<?php echo count($candidatures); ?>)</h3>
                
                <ul class="candidature-list">
                    <?php foreach ($candidatures as $candidature): ?>
                        <li class="candidature-item">
                            <span class="candidature-email"><?php echo htmlspecialchars($candidature['user_email']); ?></span>
                            <span class="candidature-status status-<?php echo strtolower($candidature['statut']) === 'en attente' ? 'pending' : (strtolower($candidature['statut']) === 'acceptée' ? 'accepted' : 'rejected'); ?>">
                                <?php echo htmlspecialchars($candidature['statut']); ?>
                            </span>
                            <div class="candidature-date">
                                Postulé le <?php echo date('d/m/Y à H:i', strtotime($candidature['date_candidature'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
    </div>
</body>
</html> 