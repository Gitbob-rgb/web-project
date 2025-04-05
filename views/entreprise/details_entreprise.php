<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($entreprise['nom']); ?> - Détails - Plateforme de Stages</title>
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
        
        .btn-edit {
            background-color: #FF9800;
        }
        
        .btn-delete {
            background-color: #F44336;
        }
        
        .info-section {
            margin-bottom: 2rem;
        }
        
        .info-section h3 {
            color: #333;
            margin-bottom: 1rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #eee;
        }
        
        .info-item {
            margin-bottom: 0.8rem;
        }
        
        .info-item strong {
            display: inline-block;
            width: 150px;
            color: #555;
        }
        
        .rating {
            color: #FFC107;
            font-weight: bold;
            font-size: 1.2rem;
        }
        
        .rating-stars {
            margin-top: 1rem;
        }
        
        .rating-stars label {
            font-size: 1.5rem;
            padding: 0 0.1rem;
            cursor: pointer;
            color: #ddd;
        }
        
        .rating-stars input[type="radio"] {
            display: none;
        }
        
        .rating-stars input[type="radio"]:checked ~ label {
            color: #FFC107;
        }
        
        .rating-stars label:hover,
        .rating-stars label:hover ~ label {
            color: #FFC107;
        }
        
        .rating-stars input[type="radio"]:checked + label:hover,
        .rating-stars input[type="radio"]:checked ~ label:hover,
        .rating-stars label:hover ~ input[type="radio"]:checked ~ label,
        .rating-stars input[type="radio"]:checked ~ label:hover ~ label {
            color: #FFC107;
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
        
        .ratings-list {
            margin-top: 1rem;
        }
        
        .rating-item {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #eee;
            border-radius: 4px;
        }
        
        .rating-item p {
            margin-bottom: 0.5rem;
        }
        
        .rating-item .user {
            color: #555;
            font-size: 0.9rem;
        }
        
        .rating-item .date {
            color: #999;
            font-size: 0.8rem;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="title-container">
                <h2><?php echo htmlspecialchars($entreprise['nom']); ?></h2>
                <div class="rating-display">
                    <?php
                        $rating = round($entreprise['moyenne_notation'], 1);
                        if ($rating > 0) {
                            echo "<span class='rating-value'>$rating / 5</span>";
                            
                            echo "<div class='stars'>";
                            for ($i = 1; $i <= 5; $i++) {
                                $starClass = $i <= $rating ? 'star filled' : 'star';
                                echo "<span class='$starClass'>★</span>";
                            }
                            echo "</div>";
                        } else {
                            echo "<span class='no-rating'>Non évaluée</span>";
                        }
                    ?>
                </div>
            </div>
            <div class="actions">
                <a href="index.php?page=liste_entreprise" class="btn">Retour à la liste</a>
                <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote')): ?>
                <a href="index.php?page=modifier_entreprise&id=<?php echo $entreprise['id']; ?>" class="btn btn-edit">Modifier</a>
                <a href="index.php?page=supprimer_entreprise&id=<?php echo $entreprise['id']; ?>" class="btn btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette entreprise ?')">Supprimer</a>
                <?php endif; ?>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Informations générales</h3>
            <div class="info-item">
                <strong>Nom :</strong> <?php echo htmlspecialchars($entreprise['nom']); ?>
            </div>
            <div class="info-item">
                <strong>Adresse :</strong> <?php echo isset($entreprise['adresse']) && $entreprise['adresse'] ? htmlspecialchars($entreprise['adresse']) : 'Non renseigné'; ?>
            </div>
            <div class="info-item">
                <strong>Email de contact :</strong> <?php echo isset($entreprise['email']) && $entreprise['email'] ? htmlspecialchars($entreprise['email']) : 'Non renseigné'; ?>
            </div>
        </div>
        
        <div class="info-section">
            <h3>Statistiques</h3>
            <div class="info-item">
                <strong>Offres de stage :</strong> <?php echo $entreprise['offre_count']; ?>
            </div>
            <div class="info-item">
                <strong>Candidatures reçues :</strong> <?php echo $entreprise['candidature_count']; ?>
            </div>
            <div class="info-item">
                <strong>Évaluation moyenne :</strong> 
                <span class="rating">
                    <?php
                        $rating = round($entreprise['moyenne_notation'], 1);
                        echo $rating > 0 ? "$rating / 5" : "Non évalué";
                    ?>
                </span>
            </div>
        </div>
        
        <div class="rating-form">
            <h3>Évaluer cette entreprise</h3>
            
            <?php if (isset($_GET['success']) && $_GET['success'] === 'rating'): ?>
                <div class="alert alert-success">Votre évaluation a été enregistrée avec succès.</div>
            <?php endif; ?>
            
            <?php if (isset($_GET['error'])): ?>
                <div class="alert alert-danger">
                    <?php
                        switch ($_GET['error']) {
                            case 'invalid_rating':
                                echo 'La note doit être comprise entre 1 et 5.';
                                break;
                            case 'rating':
                                echo 'Une erreur est survenue lors de l\'enregistrement de votre évaluation.';
                                break;
                        }
                    ?>
                </div>
            <?php endif; ?>
            
            <form action="index.php?page=noter_entreprise&id=<?php echo $entreprise['id']; ?>" method="POST">
                <div class="rating-stars">
                    <input type="radio" id="star5" name="note" value="5" />
                    <label for="star5">★</label>
                    <input type="radio" id="star4" name="note" value="4" />
                    <label for="star4">★</label>
                    <input type="radio" id="star3" name="note" value="3" />
                    <label for="star3">★</label>
                    <input type="radio" id="star2" name="note" value="2" />
                    <label for="star2">★</label>
                    <input type="radio" id="star1" name="note" value="1" />
                    <label for="star1">★</label>
                </div>
                <button type="submit" class="btn">Soumettre mon évaluation</button>
            </form>
        </div>
        
        <div class="info-section">
            <h3>Évaluations (<?php echo count($notations); ?>)</h3>
            
            <?php if (empty($notations)): ?>
                <p>Aucune évaluation pour le moment.</p>
            <?php else: ?>
                <div class="ratings-list">
                    <?php foreach ($notations as $notation): ?>
                        <div class="rating-item">
                            <p class="rating">
                                <?php
                                    $stars = '';
                                    for ($i = 1; $i <= 5; $i++) {
                                        $stars .= $i <= $notation['note'] ? '★' : '☆';
                                    }
                                    echo $stars . ' (' . $notation['note'] . '/5)';
                                ?>
                            </p>
                            <p class="user">Par <?php echo htmlspecialchars($notation['user_email']); ?></p>
                            <p class="date">Le <?php echo date('d/m/Y', strtotime($notation['date_vote'])); ?></p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html> 