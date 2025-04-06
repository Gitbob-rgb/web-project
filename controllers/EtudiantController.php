<?php
require_once 'models/EtudiantModel.php';

class EtudiantController {
    private $etudiantModel;
    
    public function __construct() {
        $this->etudiantModel = new EtudiantModel();
    }
    
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($keyword)) {
            $etudiants = $this->etudiantModel->searchEtudiants($keyword);
        } else {
            $etudiants = $this->etudiantModel->getAllEtudiants();
        }
        
        include 'views/etudiant/liste_etudiant.php';
    }
    
    public function show($id) {
        $etudiant = $this->etudiantModel->getEtudiantById($id);
        
        if (!$etudiant) {
            header('Location: index.php?page=liste_etudiant&error=etudiant_not_found');
            exit;
        }
        
        // Récupérer les candidatures de l'étudiant
        $candidatures = $this->etudiantModel->getCandidaturesByEtudiant($id);
        
        // Récupérer la wishlist de l'étudiant
        $wishlist = $this->etudiantModel->getWishlistByEtudiant($id);
        
        // Récupérer les notes données aux entreprises
        $notations = $this->etudiantModel->getEntrepriseNotesByEtudiant($id);
        
        include 'views/etudiant/details_etudiant.php';
    }
    
    public function createForm() {
        $errors = array();
        include 'views/etudiant/form_etudiant.php';
    }
    
    public function create() {
        $errors = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            $password = isset($_POST['password']) ? trim($_POST['password']) : '';
            
            // Validation
            if (empty($email)) {
                $errors['email'] = 'L\'email est obligatoire';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format d\'email invalide';
            }
            
            if (empty($password)) {
                $errors['password'] = 'Le mot de passe est obligatoire';
            }
            
            // Si pas d'erreurs, enregistrer
            if (empty($errors)) {
                if ($this->etudiantModel->addEtudiant($email, $password)) {
                    header('Location: index.php?page=liste_etudiant&success=create');
                    exit;
                } else {
                    $errors['general'] = 'Une erreur est survenue lors de l\'ajout de l\'étudiant';
                }
            }
            
            include 'views/etudiant/form_etudiant.php';
        } else {
            header('Location: index.php?page=ajouter_etudiant');
            exit;
        }
    }
    
    public function editForm($id) {
        $errors = array();
        
        $etudiant = $this->etudiantModel->getEtudiantById($id);
        
        if (!$etudiant) {
            header('Location: index.php?page=liste_etudiant&error=etudiant_not_found');
            exit;
        }
        
        include 'views/etudiant/form_etudiant.php';
    }
    
    public function update($id) {
        $errors = array();
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            
            // Validation
            if (empty($email)) {
                $errors['email'] = 'L\'email est obligatoire';
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'Format d\'email invalide';
            }
            
            // Si pas d'erreurs, mettre à jour
            if (empty($errors)) {
                if ($this->etudiantModel->updateEtudiant($id, $email)) {
                    header('Location: index.php?page=liste_etudiant&success=update');
                    exit;
                } else {
                    $errors['general'] = 'Une erreur est survenue lors de la mise à jour de l\'étudiant';
                }
            }
            
            // En cas d'erreur, récupérer l'étudiant et afficher le formulaire à nouveau
            $etudiant = $this->etudiantModel->getEtudiantById($id);
            include 'views/etudiant/form_etudiant.php';
        } else {
            header('Location: index.php?page=modifier_etudiant&id=' . $id);
            exit;
        }
    }
    
    public function delete($id) {
        // Vérifier si l'étudiant existe
        $etudiant = $this->etudiantModel->getEtudiantById($id);
        
        if (!$etudiant) {
            header('Location: index.php?page=liste_etudiant&error=etudiant_not_found');
            exit;
        }
        
        if ($this->etudiantModel->deleteEtudiant($id)) {
            header('Location: index.php?page=liste_etudiant&success=delete');
        } else {
            header('Location: index.php?page=liste_etudiant&error=delete');
        }
        exit;
    }
    
    public function resetPassword($id) {
        try {
            // Vérifier si l'étudiant existe
            $etudiant = $this->etudiantModel->getEtudiantById($id);
            
            if (!$etudiant) {
                header('Location: index.php?page=liste_etudiant&error=etudiant_not_found');
                exit;
            }
            
            // Réinitialiser le mot de passe
            $new_password = $this->etudiantModel->resetPassword($id);
            
            if ($new_password) {
                // Dans une application réelle, on enverrait ce mot de passe par email
                // Pour l'exemple, on l'affiche à l'utilisateur
                header('Location: index.php?page=liste_etudiant&success=reset&password=' . $new_password);
            } else {
                header('Location: index.php?page=liste_etudiant&error=reset');
            }
            exit;
        } catch (Exception $e) {
            // Journaliser l'erreur pour l'administration
            error_log("Erreur lors de la réinitialisation du mot de passe étudiant (ID: $id): " . $e->getMessage());
            
            // Rediriger avec un message d'erreur
            header('Location: index.php?page=liste_etudiant&error=reset_error');
            exit;
        }
    }
} 