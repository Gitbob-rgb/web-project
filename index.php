<?php
// Démarrer la session
session_start();

// Inclure les contrôleurs
require_once 'controllers/AuthController.php';
require_once 'controllers/EntrepriseController.php';
require_once 'controllers/OffreController.php';
require_once 'controllers/EtudiantController.php';
require_once 'controllers/PiloteController.php';

// Fonction pour vérifier si l'utilisateur est connecté
function isLoggedIn() {
    return isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true;
}

// Fonction pour vérifier si l'utilisateur a le rôle requis
function hasRole($roles) {
    if (!isLoggedIn()) return false;
    
    if (!is_array($roles)) {
        $roles = [$roles];
    }
    
    return in_array($_SESSION['user_role'], $roles);
}

// Fonction pour vérifier si l'utilisateur a le droit de modifier/supprimer une entreprise ou une offre
function canModifyEntreprise() {
    if (!isLoggedIn()) return false;
    return $_SESSION['user_role'] === 'admin' || $_SESSION['user_role'] === 'pilote';
}

// Créer les instances de contrôleurs
$authController = new AuthController();
$entrepriseController = new EntrepriseController();
$offreController = new OffreController();
$etudiantController = new EtudiantController();
$piloteController = new PiloteController();

// Routage simple
$page = isset($_GET['page']) ? $_GET['page'] : 'login';
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? (int)$_GET['id'] : null;

// Traiter la demande
switch ($page) {
    // Routes d'authentification
    case 'login':
        if ($action === 'submit') {
            $authController->login();
        } else {
            $authController->showLoginForm();
        }
        break;
        
    case 'logout':
        $authController->logout();
        break;
        
    case 'dashboard':
        if (isLoggedIn() && hasRole('admin')) {
            include 'views/dashboard.php';
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'dashboard_etudiant':
        if (isLoggedIn() && hasRole('etudiant')) {
            include 'views/dashboard_etudiant.php';
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'dashboard_pilote':
        if (isLoggedIn() && hasRole('pilote')) {
            include 'views/dashboard_pilote.php';
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
    
    // Routes des entreprises
    case 'liste_entreprise':
        if (isLoggedIn()) {
            $entrepriseController->index();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'voir_entreprise':
        if (isLoggedIn() && $id) {
            $entrepriseController->show($id);
        } else {
            header('Location: index.php?page=liste_entreprise');
            exit;
        }
        break;
        
    case 'ajouter_entreprise':
        if (isLoggedIn() && hasRole(['admin', 'pilote'])) {
            $entrepriseController->createForm();
        } else {
            header('Location: index.php?page=liste_entreprise');
            exit;
        }
        break;
        
    case 'ajouter_entreprise_submit':
        if (isLoggedIn() && hasRole(['admin', 'pilote'])) {
            $entrepriseController->create();
        } else {
            header('Location: index.php?page=liste_entreprise');
            exit;
        }
        break;
        
    case 'modifier_entreprise':
        if (isLoggedIn() && hasRole(['admin', 'pilote']) && $id) {
            $entrepriseController->editForm($id);
        } else {
            header('Location: index.php?page=liste_entreprise&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_entreprise_submit':
        if (isLoggedIn() && canModifyEntreprise() && $id) {
            $entrepriseController->update($id);
        } else {
            header('Location: index.php?page=liste_entreprise&error=permission_denied');
            exit;
        }
        break;
        
    case 'supprimer_entreprise':
        if (isLoggedIn() && canModifyEntreprise() && $id) {
            $entrepriseController->delete($id);
        } else {
            header('Location: index.php?page=liste_entreprise&error=permission_denied');
            exit;
        }
        break;
        
    case 'noter_entreprise':
        if (isLoggedIn() && $id) {
            $entrepriseController->rate($id);
        } else {
            header('Location: index.php?page=liste_entreprise');
            exit;
        }
        break;
    
    // Routes des offres de stage
    case 'liste_offre':
        if (isLoggedIn()) {
            $offreController->index();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'voir_offre':
        if (isLoggedIn() && $id) {
            $offreController->show($id);
        } else {
            header('Location: index.php?page=liste_offre');
            exit;
        }
        break;
        
    case 'ajouter_offre':
        if (isLoggedIn() && canModifyEntreprise()) {
            $offreController->createForm();
        } else {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        break;
        
    case 'ajouter_offre_submit':
        if (isLoggedIn() && canModifyEntreprise()) {
            $offreController->create();
        } else {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_offre':
        if (isLoggedIn() && canModifyEntreprise() && $id) {
            $offreController->editForm($id);
        } else {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_offre_submit':
        if (isLoggedIn() && canModifyEntreprise() && $id) {
            $offreController->update($id);
        } else {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        break;
        
    case 'supprimer_offre':
        if (isLoggedIn() && canModifyEntreprise() && $id) {
            $offreController->delete($id);
        } else {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        break;
        
    case 'postuler_offre':
        if (isLoggedIn() && $id) {
            $offreController->postuler($id);
        } else {
            header('Location: index.php?page=voir_offre&id=' . $id . '&error=permission_denied');
            exit;
        }
        break;
        
    case 'ajouter_wishlist':
        if (isLoggedIn() && (hasRole('etudiant') || hasRole('admin')) && $id) {
            $offreController->ajouterWishlist($id);
        } else {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        break;
        
    case 'wishlist':
        if (isLoggedIn() && (hasRole('etudiant')|| hasRole('admin'))) {
            $offreController->showWishlist();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'supprimer_wishlist':
        if (isLoggedIn() && (hasRole('etudiant')|| hasRole('admin'))) {
            $offreController->removeFromWishlist($id);
        } else {
            header('Location: index.php?page=wishlist&error=permission_denied');
            exit;
        }
        break;
        
    case 'soumettre_candidature':
        if (isLoggedIn() && $id) {
            $offreController->soumettreCandidature($id);
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
    
    // Routes des étudiants
    case 'liste_etudiant':
        if (isLoggedIn()) {
            $etudiantController->index();
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'voir_etudiant':
        if (isLoggedIn() && $id) {
            $etudiantController->show($id);
        } else {
            header('Location: index.php?page=liste_etudiant');
            exit;
        }
        break;
        
    case 'ajouter_etudiant':
        if (isLoggedIn() && hasRole(['admin', 'pilote'])) {
            $etudiantController->createForm();
        } else {
            header('Location: index.php?page=liste_etudiant&error=permission_denied');
            exit;
        }
        break;
        
    case 'ajouter_etudiant_submit':
        if (isLoggedIn() && hasRole(['admin', 'pilote'])) {
            $etudiantController->create();
        } else {
            header('Location: index.php?page=liste_etudiant&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_etudiant':
        if (isLoggedIn() && hasRole(['admin', 'pilote']) && $id) {
            $etudiantController->editForm($id);
        } else {
            header('Location: index.php?page=liste_etudiant&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_etudiant_submit':
        if (isLoggedIn() && hasRole(['admin', 'pilote']) && $id) {
            $etudiantController->update($id);
        } else {
            header('Location: index.php?page=liste_etudiant&error=permission_denied');
            exit;
        }
        break;
        
    case 'supprimer_etudiant':
        if (isLoggedIn() && hasRole(['admin', 'pilote']) && $id) {
            $etudiantController->delete($id);
        } else {
            header('Location: index.php?page=liste_etudiant&error=permission_denied');
            exit;
        }
        break;
        
    case 'reset_password_etudiant':
        if (isLoggedIn() && hasRole(['admin', 'pilote']) && $id) {
            $etudiantController->resetPassword($id);
        } else {
            header('Location: index.php?page=liste_etudiant&error=permission_denied');
            exit;
        }
        break;
    
    // Routes des pilotes
    case 'liste_pilote':
        if (isLoggedIn() && hasRole('admin')) {
            $piloteController->index();
        } else {
            header('Location: index.php?page=dashboard&error=permission_denied');
            exit;
        }
        break;
        
    case 'details_pilote':
        if (isLoggedIn() && hasRole('admin') && $id) {
            $piloteController->show($id);
        } else {
            header('Location: index.php?page=dashboard&error=permission_denied');
            exit;
        }
        break;
        
    case 'ajouter_pilote':
        if (isLoggedIn() && hasRole(['admin'])) {
            $piloteController->createForm();
        } else {
            header('Location: index.php?page=liste_pilote&error=permission_denied');
            exit;
        }
        break;
        
    case 'ajouter_pilote_submit':
        if (isLoggedIn() && hasRole(['admin'])) {
            $piloteController->create();
        } else {
            header('Location: index.php?page=liste_pilote&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_pilote':
        if (isLoggedIn() && hasRole(['admin']) && $id) {
            $piloteController->editForm($id);
        } else {
            header('Location: index.php?page=liste_pilote&error=permission_denied');
            exit;
        }
        break;
        
    case 'modifier_pilote_submit':
        if (isLoggedIn() && hasRole(['admin']) && $id) {
            $piloteController->update($id);
        } else {
            header('Location: index.php?page=liste_pilote&error=permission_denied');
            exit;
        }
        break;
        
    case 'supprimer_pilote':
        if (isLoggedIn() && hasRole(['admin']) && $id) {
            $piloteController->delete($id);
        } else {
            header('Location: index.php?page=liste_pilote&error=permission_denied');
            exit;
        }
        break;
        
    case 'reset_password_pilote':
        if (isLoggedIn() && hasRole(['admin']) && $id) {
            $piloteController->resetPassword($id);
        } else {
            header('Location: index.php?page=liste_pilote&error=permission_denied');
            exit;
        }
        break;
    
    // Autres routes existantes
    case 'liste_utilisateur':
        if (isLoggedIn()) {
            include 'views/liste_utilisateur.php';
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    case 'liste_admin':
        if (isLoggedIn()) {
            include 'views/liste_admin.php';
        } else {
            header('Location: index.php?page=login');
            exit;
        }
        break;
        
    default:
        // Page par défaut - rediriger vers login
        header('Location: index.php?page=login');
        exit;
}
?> 