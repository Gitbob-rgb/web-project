<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord Étudiant - Plateforme de Stages</title>
    <link rel="stylesheet" href="public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <style>
        .dashboard-container {
            background-color: white;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 2rem;
            width: 100%;
            max-width: 1000px;
            margin: 0 auto;
        }

        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
        }

        .user-info {
            display: flex;
            align-items: center;
            margin-bottom: 1rem;
        }

        .user-info .avatar {
            width: 50px;
            height: 50px;
            background-color: #4CAF50;
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            margin-right: 1rem;
        }

        .user-info .details h3 {
            margin: 0;
            color: #333;
        }

        .user-info .details p {
            margin: 0;
            color: #666;
        }

        .logout-button {
            background-color: #f44336;
            border: none;
            color: white;
            padding: 0.6rem 1.2rem;
            border-radius: 4px;
            cursor: pointer;
            font-size: 1rem;
            transition: background-color 0.3s;
        }

        .logout-button:hover {
            background-color: #d32f2f;
        }
        
        .modules-container {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -0.5rem;
        }
        
        .module {
            flex: 1;
            min-width: 200px;
            margin: 0.5rem;
            padding: 1.5rem;
            border-radius: 5px;
            background-color: #f5f5f5;
            transition: transform 0.3s;
        }
        
        .module:hover {
            transform: translateY(-5px);
        }
        
        .module h3 {
            margin-top: 0;
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 1rem;
        }
        
        .module-links {
            display: flex;
            flex-direction: column;
        }
        
        .module-link {
            display: block;
            padding: 0.8rem;
            margin-bottom: 0.5rem;
            background-color: white;
            color: #2196F3;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        
        .module-link:hover {
            background-color: #e3f2fd;
        }
        
        .module-link.highlight {
            background-color: #e8f5e9;
            color: #43A047;
            font-weight: bold;
        }
        
        .role-badge {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 20px;
            font-size: 0.8rem;
            margin-left: 0.5rem;
        }
        
        .role-etudiant {
            background-color: #4CAF50;
            color: white;
        }
        
        .alert {
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
        
        .alert-info {
            background-color: #e3f2fd;
            border-color: #bbdefb;
            color: #0d47a1;
        }
        
        /* Menu burger */
        .menu-toggle {
            display: none;
            flex-direction: column;
            justify-content: space-between;
            width: 30px;
            height: 21px;
            cursor: pointer;
        }
        
        .menu-toggle span {
            display: block;
            height: 3px;
            width: 100%;
            border-radius: 3px;
            background-color: #333;
        }
        
        /* Media queries pour responsive */
        @media (max-width: 768px) {
            .dashboard-container {
                padding: 1rem;
            }
            
            .modules-container {
                flex-direction: column;
            }
            
            .module {
                width: 100%;
                margin: 0.5rem 0;
            }
            
            .dashboard-header h2 {
                font-size: 1.5rem;
            }
            
            .menu-toggle {
                display: flex;
                margin-right: 1rem;
            }
            
            .modules-container {
                display: none;
            }
            
            .modules-container.active {
                display: flex;
            }
            
            .user-info {
                flex-direction: column;
                text-align: center;
            }
            
            .user-info .avatar {
                margin-right: 0;
                margin-bottom: 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <div class="dashboard-header">
            <div style="display: flex; align-items: center;">
                <div class="menu-toggle" id="menu-toggle">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
                <h2>Tableau de bord Étudiant</h2>
            </div>
            <a href="index.php?page=logout">
                <button class="logout-button">Déconnexion</button>
            </a>
        </div>
        
        <div class="user-info">
            <div class="avatar">
                <?php echo substr($_SESSION['user_email'], 0, 1); ?>
            </div>
            <div class="details">
                <h3>
                    <?php echo htmlspecialchars($_SESSION['user_email']); ?>
                    <span class="role-badge role-etudiant">
                        Étudiant
                    </span>
                </h3>
                <p>Dernière connexion : <?php echo date('d/m/Y H:i'); ?></p>
            </div>
        </div>
        
        <div class="alert alert-info">
            <strong>Bienvenue dans votre espace étudiant!</strong> Consultez les offres de stage disponibles, gérez votre wishlist et découvrez les entreprises partenaires.
        </div>
        
        <div class="modules-container" id="modules-container">
            <!-- Module Offres de stage -->
            <div class="module">
                <h3>Offres de stage</h3>
                <div class="module-links">
                    <a href="index.php?page=liste_offre" class="module-link highlight">Liste des offres de stage</a>
                    <a href="index.php?page=wishlist" class="module-link highlight">Ma wishlist</a>
                </div>
            </div>
            
            <!-- Module Entreprises -->
            <div class="module">
                <h3>Entreprises</h3>
                <div class="module-links">
                    <a href="index.php?page=liste_entreprise" class="module-link highlight">Liste des entreprises</a>
                </div>
            </div>
            
            <!-- Module Candidatures -->
            <div class="module">
                <h3>Mes candidatures</h3>
                <div class="module-links">
                    <a href="index.php?page=mes_candidatures" class="module-link highlight">Voir mes candidatures</a>
                </div>
            </div>
            
            <!-- Module Profil -->
            <div class="module">
                <h3>Mon profil</h3>
                <div class="module-links">
                    <a href="index.php?page=mon_profil" class="module-link highlight">Modifier mon profil</a>
                    <a href="index.php?page=changer_mot_de_passe" class="module-link">Changer mot de passe</a>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const menuToggle = document.getElementById('menu-toggle');
            const modulesContainer = document.getElementById('modules-container');
            
            menuToggle.addEventListener('click', function() {
                modulesContainer.classList.toggle('active');
            });
        });
    </script>
</body>
</html> 