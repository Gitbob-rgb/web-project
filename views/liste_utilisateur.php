<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des utilisateurs</title>
    <link rel="stylesheet" href="public/css/style.css">
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
        
        .back-button {
            background-color: #607D8B;
            padding: 0.5rem 1rem;
        }
        
        .back-button:hover {
            background-color: #455A64;
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
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Liste des utilisateurs</h2>
            <a href="index.php?page=dashboard">
                <button class="back-button">Retour au tableau de bord</button>
            </a>
        </div>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nom d'utilisateur</th>
                    <th>Rôle</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>admin</td>
                    <td>Administrateur</td>
                    <td><button class="back-button">Modifier</button></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>user</td>
                    <td>Utilisateur</td>
                    <td><button class="back-button">Modifier</button></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>cat</td>
                    <td>Utilisateur</td>
                    <td><button class="back-button">Modifier</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</body>
</html> 