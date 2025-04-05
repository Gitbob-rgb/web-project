<?php
require_once 'models/UserModel.php';

class AuthController {
    private $userModel;
    
    public function __construct() {
        $this->userModel = new UserModel();
    }
    
    public function showLoginForm($error = '') {
        // Inclure la vue de connexion en passant le message d'erreur
        include 'views/login.php';
    }
    
    public function login() {
        // Traiter la soumission du formulaire de connexion
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? $_POST['email'] : '';
            $password = isset($_POST['password']) ? $_POST['password'] : '';
            
            if ($this->userModel->verifyCredentials($email, $password)) {
                // Authentification réussie - initialiser la session
                session_start();
                
                // Récupérer les informations de l'utilisateur
                $user = $this->userModel->getUserByEmail($email);
                
                if ($user) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $email;
                    $_SESSION['user_role'] = $user['role'];
                    $_SESSION['loggedin'] = true;
                
                    // Rediriger vers le tableau de bord correspondant au rôle
                    if ($_SESSION['user_role'] === 'etudiant') {
                        header('Location: index.php?page=dashboard_etudiant');
                    } elseif ($_SESSION['user_role'] === 'pilote') {
                        header('Location: index.php?page=dashboard_pilote');
                    } else {
                        // Administrateur ou autre rôle
                        header('Location: index.php?page=dashboard');
                    }
                    exit;
                } else {
                    // Si par un hasard improbable l'utilisateur n'est pas trouvé
                    $this->showLoginForm('Erreur lors de la récupération des données utilisateur.');
                }
            } else {
                // Échec de l'authentification - afficher à nouveau le formulaire avec un message d'erreur
                $this->showLoginForm('Email ou mot de passe incorrect.');
            }
        } else {
            // Si accédé directement sans POST, afficher simplement le formulaire
            $this->showLoginForm();
        }
    }
    
    public function logout() {
        // Détruire la session et rediriger vers la page de connexion
        session_start();
        session_destroy();
        header('Location: index.php?page=login');
        exit;
    }
}
?> 