<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de bord - Plateforme de Stages</title>
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
            background-color: #2196F3;
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
        
        .module-link.disabled {
            color: #999;
            cursor: not-allowed;
            background-color: #f5f5f5;
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
        
        .role-admin {
            background-color: #F44336;
            color: white;
        }
        
        .role-pilote {
            background-color: #FF9800;
            color: white;
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

        .footer .grid{
   display: grid;
   grid-template-columns: repeat(auto-fit, 30rem);
   gap: 1.5rem;
   justify-content: center;
   align-items: flex-start;
}

.footer .grid .box h3{
   font-size: 2rem;
   color: var(--white);
   margin: 1rem 0;
   padding-bottom: 1rem;
   text-transform: capitalize;
}

.footer .grid .box a{
   display: block;
   padding: 1.5rem 0;
   font-size: 1.6rem;
   color: var(--light-color);
}

.footer .grid .box a i{
   color: var(--main-color);
   margin-right: .8rem;
   transition: .2s linear;
}

.footer .grid .box a:hover i{
   margin-right: 2rem;
}

.footer .credit{
   text-align: center;
   padding:3rem 2rem;
   border-top: var(--border);
   background-color: var(--black);
   font-size: 2rem;
   color: var(--light-color);
   line-height: 1.5;
   /* padding-bottom: 10rem; */
}

.footer .credit span{
   color: var(--main-color);
   text-transform: capitalize;
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
                <h2>Tableau de bord</h2>
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
                    <span class="role-badge role-<?php echo $_SESSION['user_role']; ?>">
                        <?php echo ucfirst($_SESSION['user_role']); ?>
                    </span>
                </h3>
                <p>Dernière connexion : <?php echo date('d/m/Y H:i'); ?></p>
            </div>
        </div>
        
        
        
        <div class="modules-container" id="modules-container">
            <!-- Module Entreprises -->
            <div class="module">
                <h3>Gestion des entreprises</h3>
                <div class="module-links">
                    <a href="index.php?page=liste_entreprise" class="module-link highlight">Liste des entreprises</a>
                    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote'): ?>
                        <a href="index.php?page=ajouter_entreprise" class="module-link highlight">Ajouter une entreprise</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Module Étudiants -->
            <div class="module">
                <h3>Gestion des étudiants</h3>
                <div class="module-links">
                    <a href="index.php?page=liste_etudiant" class="module-link highlight">Liste des étudiants</a>
                    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote'): ?>
                        <a href="index.php?page=ajouter_etudiant" class="module-link highlight">Ajouter un étudiant</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Module Offres de stage -->
            <div class="module">
                <h3>Gestion des offres de stage</h3>
                <div class="module-links">
                    <a href="index.php?page=liste_offre" class="module-link highlight">Liste des offres de stage</a>
                    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote'): ?>
                        <a href="index.php?page=ajouter_offre" class="module-link highlight">Ajouter une offre de stage</a>
                    <?php endif; ?>
                    <?php if ($_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'etudiant'): ?>
                        <a href="index.php?page=wishlist" class="module-link highlight">Ma wishlist</a>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Module Pilotes -->
            <?php if ($_SESSION['user_role'] === 'admin'): ?>
            <div class="module">
                <h3>Gestion des pilotes</h3>
                <div class="module-links">
                    <a href="index.php?page=liste_pilote" class="module-link highlight">Liste des pilotes</a>
                    <a href="index.php?page=ajouter_pilote" class="module-link highlight">Ajouter un pilote</a>
                </div>
            </div>
            <?php endif; ?>
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
<style>
    .footer {
        background-color: #4CAF50;
        color: white;
        padding: 1rem 0;
        margin-top: auto;
        width: 100%;
    }

    .footer-content {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 2rem;
    }

    .footer-nav a {
        color: white;
        text-decoration: none;
    }

    .footer-nav a:hover {
        text-decoration: underline;
    }

    .dashboard-wrapper {
        min-height: 100vh;
        display: flex;
        flex-direction: column;
    }

    .dashboard-container {
        flex: 1;
        margin-bottom: 2rem;
    }
</style>
