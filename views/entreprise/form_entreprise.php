<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($entreprise) ? 'Modifier' : 'Ajouter'; ?> une entreprise - Plateforme de Stages</title>
    <link rel="stylesheet" href="../../public/css/style.css">
    <style>
        .container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 600px;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo isset($entreprise) ? 'Modifier' : 'Ajouter'; ?> une entreprise</h2>
            <a href="index.php?page=liste_entreprise" class="btn">Retour à la liste</a>
        </div>
        
        <?php if (isset($error) && !empty($error)): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form action="index.php?page=<?php echo isset($entreprise) ? 'modifier_entreprise_submit&id='.$entreprise['id'] : 'ajouter_entreprise_submit'; ?>" method="POST">
            <div class="form-group">
                <label for="nom">Nom de l'entreprise *</label>
                <input type="text" id="nom" name="nom" value="<?php echo isset($entreprise) ? htmlspecialchars($entreprise['nom']) : ''; ?>" required>
                <?php if (isset($errors) && isset($errors['nom'])): ?>
                    <div class="error"><?php echo $errors['nom']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <label for="adresse">Adresse</label>
                <textarea id="adresse" name="adresse" rows="3"><?php echo isset($entreprise) && isset($entreprise['adresse']) ? htmlspecialchars($entreprise['adresse']) : ''; ?></textarea>
            </div>
            
            <div class="form-group">
                <label for="email">Email de contact</label>
                <input type="email" id="email" name="email" value="<?php echo isset($entreprise) && isset($entreprise['email']) ? htmlspecialchars($entreprise['email']) : ''; ?>">
                <?php if (isset($errors) && isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            
            <div class="form-group">
                <button type="submit" class="btn-submit"><?php echo isset($entreprise) ? 'Mettre à jour' : 'Ajouter'; ?> l'entreprise</button>
            </div>
        </form>
    </div>
</body>
</html> 