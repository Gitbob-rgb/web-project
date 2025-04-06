<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($etudiant) ? 'Modifier' : 'Ajouter'; ?> un étudiant - Plateforme de Stages</title>
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
        
        .btn-primary {
            background-color: #2196F3;
        }
        
        .btn:hover {
            opacity: 0.9;
        }
        
        form {
            display: flex;
            flex-direction: column;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: bold;
        }
        
        input, select, textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 1rem;
        }
        
        textarea {
            height: 100px;
        }
        
        .error {
            color: #dc3545;
            font-size: 0.9rem;
            margin-top: 0.25rem;
        }
        
        .actions {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }
        
        .required {
            color: #dc3545;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2><?php echo isset($etudiant) ? 'Modifier' : 'Ajouter'; ?> un étudiant</h2>
            <a href="index.php?page=liste_etudiant" class="btn">Retour à la liste</a>
        </div>
        
        <?php if (isset($errors['general'])): ?>
            <div class="alert alert-danger"><?php echo $errors['general']; ?></div>
        <?php endif; ?>
        
        <form action="index.php?page=<?php echo isset($etudiant) ? 'modifier_etudiant_submit&id=' . $etudiant['id'] : 'ajouter_etudiant_submit'; ?>" method="POST">
            <div class="form-group">
                <label for="email">Email <span class="required">*</span></label>
                <input type="email" id="email" name="email" value="<?php echo isset($etudiant) ? htmlspecialchars($etudiant['email']) : ''; ?>" required>
                <?php if (isset($errors['email'])): ?>
                    <div class="error"><?php echo $errors['email']; ?></div>
                <?php endif; ?>
            </div>
            
            <?php if (!isset($etudiant)): ?>
            <div class="form-group">
                <label for="password">Mot de passe <span class="required">*</span></label>
                <input type="password" id="password" name="password" required>
                <?php if (isset($errors['password'])): ?>
                    <div class="error"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>
            <?php endif; ?>
            
            <div class="actions">
                <a href="index.php?page=liste_etudiant" class="btn">Annuler</a>
                <button type="submit" class="btn btn-primary"><?php echo isset($etudiant) ? 'Mettre à jour' : 'Ajouter'; ?></button>
            </div>
        </form>
    </div>
</body>
</html> 