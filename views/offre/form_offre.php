<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($offre) ? 'Modifier' : 'Ajouter'; ?> une offre de stage - Plateforme de Stages</title>
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
        input[type="date"],
        select,
        textarea {
            width: 100%;
            padding: 0.8rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        textarea {
            height: 150px;
            resize: vertical;
        }
        
        .error {
            color: #F44336;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo isset($offre) ? 'Modifier' : 'Ajouter'; ?> une offre de stage</h2>
            <a href="index.php?page=liste_offre" class="btn">Retour à la liste</a>
        </div>
        
        <?php if (isset($error) && !empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="index.php?page=<?php echo isset($offre) ? 'modifier_offre_submit&id='.$offre['id'] : 'ajouter_offre_submit'; ?>" method="POST">
            <div class="form-group">
                <label for="titre">Titre de l'offre <span class="required">*</span></label>
                <input type="text" id="titre" name="titre" value="<?php echo isset($offre) ? htmlspecialchars($offre['titre']) : ''; ?>" required>
                <?php if (isset($errors) && isset($errors['titre'])): ?>
                    <div class="error"><?php echo $errors['titre']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="entreprise_id">Entreprise <span class="required">*</span></label>
                <select id="entreprise_id" name="entreprise_id" required>
                    <option value="">Sélectionner une entreprise</option>
                    <?php foreach ($entreprises as $entreprise): ?>
                        <option value="<?php echo $entreprise['id']; ?>" <?php echo isset($offre) && $offre['entreprise_id'] == $entreprise['id'] ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($entreprise['nom']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <?php if (isset($errors) && isset($errors['entreprise_id'])): ?>
                    <div class="error"><?php echo $errors['entreprise_id']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" rows="5"><?php echo isset($offre) ? htmlspecialchars($offre['description']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="specialite">Spécialité</label>
                <input type="text" id="specialite" name="specialite" placeholder="Ex: Développement web, Marketing digital..." value="<?php echo isset($offre) ? htmlspecialchars($offre['specialite']) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="date_debut">Date de début</label>
                <input type="date" id="date_debut" name="date_debut" value="<?php echo isset($offre) && $offre['date_debut'] ? date('Y-m-d', strtotime($offre['date_debut'])) : ''; ?>">
            </div>
            
            <div class="form-group">
                <label for="date_fin">Date de fin</label>
                <input type="date" id="date_fin" name="date_fin" value="<?php echo isset($offre) && $offre['date_fin'] ? date('Y-m-d', strtotime($offre['date_fin'])) : ''; ?>">
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit"><?php echo isset($offre) ? 'Mettre à jour' : 'Ajouter'; ?> l'offre</button>
            </div>
        </form>
    </div>
</body>
</html> 