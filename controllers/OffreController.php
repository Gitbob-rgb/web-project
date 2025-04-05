<?php
require_once 'models/OffreModel.php';
require_once 'models/EntrepriseModel.php';

class OffreController {
    private $offreModel;
    private $entrepriseModel;
    
    public function __construct() {
        $this->offreModel = new OffreModel();
        $this->entrepriseModel = new EntrepriseModel();
    }
    
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($keyword)) {
            $offres = $this->offreModel->searchOffres($keyword);
        } else {
            $offres = $this->offreModel->getAllOffres();
        }
        
        include 'views/offre/liste_offre.php';
    }
    
    public function show($id) {
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=liste_offre&error=offre_not_found');
            exit;
        }
        
        $entreprise = $this->entrepriseModel->getEntrepriseById($offre['entreprise_id']);
        $candidatures = $this->offreModel->getCandidaturesByOffre($id);
        
        include 'views/offre/details_offre.php';
    }
    
    public function createForm() {
        $errors = array();
        
        // Récupérer la liste des entreprises pour le formulaire
        $entreprises = $this->entrepriseModel->getAllEntreprises();
        
        include 'views/offre/form_offre.php';
    }
    
    public function create() {
        $errors = array();
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = isset($_POST['titre']) ? trim($_POST['titre']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $entreprise_id = isset($_POST['entreprise_id']) ? (int)$_POST['entreprise_id'] : 0;
            $date_debut = isset($_POST['date_debut']) ? trim($_POST['date_debut']) : '';
            $date_fin = isset($_POST['date_fin']) ? trim($_POST['date_fin']) : '';
            $specialite = isset($_POST['specialite']) ? trim($_POST['specialite']) : '';
            
            // Validation
            if (empty($titre)) {
                $errors['titre'] = 'Le titre de l\'offre est obligatoire';
            }
            
            if (empty($entreprise_id)) {
                $errors['entreprise_id'] = 'Veuillez sélectionner une entreprise';
            }
            
            // Si pas d'erreurs, enregistrer
            if (empty($errors)) {
                if ($this->offreModel->addOffre($titre, $description, $entreprise_id, $date_debut, $date_fin, $specialite)) {
                    header('Location: index.php?page=liste_offre&success=create');
                    exit;
                } else {
                    $error = 'Une erreur est survenue lors de l\'ajout de l\'offre';
                }
            }
            
            // En cas d'erreur, récupérer la liste des entreprises et afficher le formulaire à nouveau
            $entreprises = $this->entrepriseModel->getAllEntreprises();
            include 'views/offre/form_offre.php';
        } else {
            header('Location: index.php?page=ajouter_offre');
            exit;
        }
    }
    
    public function editForm($id) {
        // Vérifier que l'utilisateur n'est pas un étudiant
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        
        $errors = array();
        
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=liste_offre&error=offre_not_found');
            exit;
        }
        
        // Récupérer la liste des entreprises pour le formulaire
        $entreprises = $this->entrepriseModel->getAllEntreprises();
        
        include 'views/offre/form_offre.php';
    }
    
    public function update($id) {
        // Vérifier que l'utilisateur n'est pas un étudiant
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        
        $errors = array();
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $titre = isset($_POST['titre']) ? trim($_POST['titre']) : '';
            $description = isset($_POST['description']) ? trim($_POST['description']) : '';
            $entreprise_id = isset($_POST['entreprise_id']) ? (int)$_POST['entreprise_id'] : 0;
            $date_debut = isset($_POST['date_debut']) ? trim($_POST['date_debut']) : '';
            $date_fin = isset($_POST['date_fin']) ? trim($_POST['date_fin']) : '';
            $specialite = isset($_POST['specialite']) ? trim($_POST['specialite']) : '';
            
            // Validation
            if (empty($titre)) {
                $errors['titre'] = 'Le titre de l\'offre est obligatoire';
            }
            
            if (empty($entreprise_id)) {
                $errors['entreprise_id'] = 'Veuillez sélectionner une entreprise';
            }
            
            // Si pas d'erreurs, mettre à jour
            if (empty($errors)) {
                if ($this->offreModel->updateOffre($id, $titre, $description, $entreprise_id, $date_debut, $date_fin, $specialite)) {
                    header('Location: index.php?page=liste_offre&success=update');
                    exit;
                } else {
                    $error = 'Une erreur est survenue lors de la mise à jour de l\'offre';
                }
            }
            
            // En cas d'erreur, récupérer l'offre et afficher le formulaire à nouveau
            $offre = $this->offreModel->getOffreById($id);
            $entreprises = $this->entrepriseModel->getAllEntreprises();
            include 'views/offre/form_offre.php';
        } else {
            header('Location: index.php?page=modifier_offre&id=' . $id);
            exit;
        }
    }
    
    public function delete($id) {
        // Vérifier que l'utilisateur n'est pas un étudiant
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        
        // Vérifier si l'offre existe
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=liste_offre&error=offre_not_found');
            exit;
        }
        
        if ($this->offreModel->deleteOffre($id)) {
            header('Location: index.php?page=liste_offre&success=delete');
        } else {
            header('Location: index.php?page=liste_offre&error=delete');
        }
        exit;
    }
    
    public function postuler($id) {
        // Vérifier si l'offre existe
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=liste_offre&error=offre_not_found');
            exit;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Vérifier si l'utilisateur a déjà postulé
        if ($this->offreModel->hasPostule($id, $_SESSION['user_id'])) {
            header('Location: index.php?page=liste_offre&error=already_applied');
            exit;
        }
        
        // Inclure la vue du formulaire de candidature
        include 'views/offre/form_candidature.php';
    }
    
    public function soumettreCandidature($id) {
        // Vérifier si l'offre existe
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=liste_offre&error=offre_not_found');
            exit;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Vérifier si l'utilisateur a déjà postulé
        if ($this->offreModel->hasPostule($id, $_SESSION['user_id'])) {
            header('Location: index.php?page=liste_offre&error=already_applied');
            exit;
        }
        
        $errors = array();
        $error_message = '';
        
        // Validation des champs du formulaire
        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
        $prenom = isset($_POST['prenom']) ? trim($_POST['prenom']) : '';
        
        if (empty($nom)) {
            $errors['nom'] = 'Le nom est obligatoire';
        }
        
        if (empty($prenom)) {
            $errors['prenom'] = 'Le prénom est obligatoire';
        }
        
        // Validation des fichiers
        $allowed_extensions = array('pdf', 'docx', 'png', 'jpg', 'jpeg');
        $max_size = 5 * 1024 * 1024; // 5 Mo en octets
        
        // Validation du CV
        if (!isset($_FILES['cv']) || $_FILES['cv']['error'] != 0) {
            $errors['cv'] = 'Vous devez fournir un CV';
        } else {
            $cv_info = pathinfo($_FILES['cv']['name']);
            $cv_extension = strtolower($cv_info['extension']);
            
            if (!in_array($cv_extension, $allowed_extensions)) {
                $errors['cv'] = 'Format de fichier non autorisé. Formats acceptés: ' . implode(', ', $allowed_extensions);
            }
            
            if ($_FILES['cv']['size'] > $max_size) {
                $errors['cv'] = 'Le fichier est trop volumineux. Taille maximale: 5 Mo';
            }
        }
        
        // Validation de la lettre de motivation
        if (!isset($_FILES['lettre_motivation']) || $_FILES['lettre_motivation']['error'] != 0) {
            $errors['lettre_motivation'] = 'Vous devez fournir une lettre de motivation';
        } else {
            $lm_info = pathinfo($_FILES['lettre_motivation']['name']);
            $lm_extension = strtolower($lm_info['extension']);
            
            if (!in_array($lm_extension, $allowed_extensions)) {
                $errors['lettre_motivation'] = 'Format de fichier non autorisé. Formats acceptés: ' . implode(', ', $allowed_extensions);
            }
            
            if ($_FILES['lettre_motivation']['size'] > $max_size) {
                $errors['lettre_motivation'] = 'Le fichier est trop volumineux. Taille maximale: 5 Mo';
            }
        }
        
        // Si pas d'erreurs, traiter les fichiers et enregistrer la candidature
        if (empty($errors)) {
            // Créer le dossier pour stocker les fichiers si nécessaire
            $upload_dir = 'uploads/candidatures/';
            if (!file_exists($upload_dir)) {
                mkdir($upload_dir, 0777, true);
            }
            
            // Générer des noms de fichiers uniques
            $cv_filename = $upload_dir . uniqid('cv_') . '.' . $cv_extension;
            $lm_filename = $upload_dir . uniqid('lm_') . '.' . $lm_extension;
            
            // Déplacer les fichiers téléchargés
            if (move_uploaded_file($_FILES['cv']['tmp_name'], $cv_filename) && 
                move_uploaded_file($_FILES['lettre_motivation']['tmp_name'], $lm_filename)) {
                
                // Enregistrer la candidature dans la base de données
                if ($this->offreModel->addCandidatureComplete($id, $_SESSION['user_id'], $nom, $prenom, $cv_filename, $lm_filename)) {
                    header('Location: index.php?page=liste_offre&success=apply');
                    exit;
                } else {
                    $error_message = 'Une erreur est survenue lors de l\'enregistrement de votre candidature';
                    
                    // Supprimer les fichiers en cas d'échec
                    @unlink($cv_filename);
                    @unlink($lm_filename);
                }
            } else {
                $error_message = 'Une erreur est survenue lors du téléchargement des fichiers';
            }
        }
        
        // En cas d'erreur, afficher à nouveau le formulaire
        include 'views/offre/form_candidature.php';
    }
    
    /**
     * Ajouter une offre à la wishlist de l'étudiant
     */
    public function ajouterWishlist($id) {
        // Vérifier si l'offre existe
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=liste_offre&error=offre_not_found');
            exit;
        }
        
        // Vérifier si l'utilisateur est connecté et est un étudiant ou un admin
        if (!isset($_SESSION['user_id']) || !(strtolower($_SESSION['user_role']) === 'etudiant' || $_SESSION['user_role'] === 'admin')) {
            header('Location: index.php?page=liste_offre&error=permission_denied');
            exit;
        }
        
        // Vérifier si l'offre est déjà dans la wishlist
        if ($this->offreModel->isInWishlist($id, $_SESSION['user_id'])) {
            header('Location: index.php?page=liste_offre&error=already_in_wishlist');
            exit;
        }
        
        // Ajouter l'offre à la wishlist
        if ($this->offreModel->addToWishlist($id, $_SESSION['user_id'])) {
            header('Location: index.php?page=liste_offre&success=added_to_wishlist');
        } else {
            header('Location: index.php?page=liste_offre&error=add_to_wishlist_failed');
        }
        exit;
    }
    
    /**
     * Affiche la wishlist de l'utilisateur
     */
    public function showWishlist() {
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Récupérer la wishlist de l'utilisateur
        $wishlist = $this->offreModel->getWishlistByUserId($_SESSION['user_id']);
        
        // Inclure la vue
        include 'views/offre/wishlist.php';
    }
    
    /**
     * Supprime une offre de la wishlist de l'utilisateur
     */
    public function removeFromWishlist($id) {
        // Vérifier si l'offre existe
        $offre = $this->offreModel->getOffreById($id);
        
        if (!$offre) {
            header('Location: index.php?page=wishlist&error=offre_not_found');
            exit;
        }
        
        // Vérifier si l'utilisateur est connecté
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?page=login');
            exit;
        }
        
        // Supprimer l'offre de la wishlist
        if ($this->offreModel->removeFromWishlist($id, $_SESSION['user_id'])) {
            header('Location: index.php?page=wishlist&success=remove');
        } else {
            header('Location: index.php?page=wishlist&error=remove_failed');
        }
        exit;
    }
} 