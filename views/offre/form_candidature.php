<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postuler à l'offre - Plateforme de Stages</title>
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
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        .form-group {
            margin-bottom: 1.5rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        input[type="text"],
        input[type="email"],
        input[type="file"],
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        .error {
            color: #F44336;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }
        
        .info-text {
            color: #666;
            font-size: 0.9rem;
            margin-top: 0.3rem;
        }
        
        .btn-submit {
            background-color: #4CAF50;
            border: none;
            color: white;
            padding: 0.8rem 1.5rem;
            font-size: 1rem;
            border-radius: 4px;
            cursor: pointer;
        }
        
        .btn-submit:hover {
            background-color: #45a049;
        }
        
        .required {
            color: #F44336;
        }
        
        .offer-details {
            background-color: #f9f9f9;
            padding: 1rem;
            border-radius: 4px;
            margin-bottom: a,5rem;
        }
        
        .offer-details h3 {
            margin-top: 0;
            color: #333;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
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
            <h2>Postuler à l'offre de stage</h2>
            <a href="index.php?page=liste_offre" class="btn">Retour à la liste</a>
        </div>
        
        <div class="offer-details">
            <h3><?php echo htmlspecialchars($offre['titre']); ?></h3>
            <p><strong>Entreprise:</strong> <?php echo htmlspecialchars($offre['entreprise_nom']); ?></p>
            <p><strong>Spécialité:</strong> <?php echo htmlspecialchars(isset($offre['specialite']) && $offre['specialite'] ? $offre['specialite'] : 'Non précisé'); ?></p>
            <p><strong>Période:</strong> Du <?php echo isset($offre['date_debut']) && $offre['date_debut'] ? date('d/m/Y', strtotime($offre['date_debut'])) : 'Non précisé'; ?> au <?php echo isset($offre['date_fin']) && $offre['date_fin'] ? date('d/m/Y', strtotime($offre['date_fin'])) : 'Non précisé'; ?></p>
        </div>
        
        <?php if(isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <form action="index.php?page=soumettre_candidature&id=<?php echo $offre['id']; ?>" method="POST" enctype="multipart/form-data">
            <div class="form-group">
                <label for="nom">Nom <span class="required">*</span></label>
                <input type="text" id="nom" name="nom" required>
                <?php if (isset($errors) && isset($errors['nom'])): ?>
                    <div class="error"><?php echo $errors['nom']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="prenom">Prénom <span class="required">*</span></label>
                <input type="text" id="prenom" name="prenom" required>
                <?php if (isset($errors) && isset($errors['prenom'])): ?>
                    <div class="error"><?php echo $errors['prenom']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="cv">CV <span class="required">*</span></label>
                <input type="file" id="cv" name="cv" required>
                <div class="info-text">Formats acceptés: .pdf, .docx, .png, .jpg, .jpeg. Taille maximale: 5 Mo.</div>
                <?php if (isset($errors) && isset($errors['cv'])): ?>
                    <div class="error"><?php echo $errors['cv']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="lettre_motivation">Lettre de motivation <span class="required">*</span></label>
                <input type="file" id="lettre_motivation" name="lettre_motivation" required>
                <div class="info-text">Formats acceptés: .pdf, .docx, .png, .jpg, .jpeg. Taille maximale: 5 Mo.</div>
                <?php if (isset($errors) && isset($errors['lettre_motivation'])): ?>
                    <div class="error"><?php echo $errors['lettre_motivation']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit">Soumettre ma candidature</button>
            </div>
        </form>
    </div>
</body>
</html> 