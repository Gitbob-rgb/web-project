<?php
require_once 'models/PiloteModel.php';

class PiloteController {
    private $piloteModel;
    
    public function __construct() {
        $this->piloteModel = new PiloteModel();
    }
    
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($keyword)) {
            $pilotes = $this->piloteModel->searchPilotes($keyword);
        } else {
            $pilotes = $this->piloteModel->getAllPilotes();
        }
        
        include 'views/pilote/liste_pilote.php';
    }
    
    public function show($id) {
        try {
            $pilote = $this->piloteModel->getPiloteById($id);
            
            if (!$pilote) {
                header('Location: index.php?page=liste_pilote&error=pilote_not_found');
                exit;
            }
            
            // Récupérer les candidatures liées aux entreprises gérées par le pilote
            $candidatures = $this->piloteModel->getCandidaturesByPilote($id);
            
            // Récupérer les notes données aux entreprises par le pilote
            $notations = $this->piloteModel->getEntrepriseNotesByPilote($id);
            
            include 'views/pilote/details_pilote.php';
        } catch (Exception $e) {
            header('Location: index.php?page=liste_pilote&error=show_error');
            exit;
        }
    }
    
    public function createForm() {
        $errors = array();
        include 'views/pilote/form_pilote.php';
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
                if ($this->piloteModel->addPilote($email, $password)) {
                    header('Location: index.php?page=liste_pilote&success=create');
                    exit;
                } else {
                    $errors['general'] = 'Une erreur est survenue lors de l\'ajout du pilote';
                }
            }
            
            include 'views/pilote/form_pilote.php';
        } else {
            header('Location: index.php?page=ajouter_pilote');
            exit;
        }
    }
    
    public function editForm($id) {
        $errors = array();
        
        try {
            $pilote = $this->piloteModel->getPiloteById($id);
            
            if (!$pilote) {
                header('Location: index.php?page=liste_pilote&error=pilote_not_found');
                exit;
            }
            
            include 'views/pilote/form_pilote.php';
        } catch (Exception $e) {
            header('Location: index.php?page=liste_pilote&error=edit_error');
            exit;
        }
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
                if ($this->piloteModel->updatePilote($id, $email)) {
                    header('Location: index.php?page=liste_pilote&success=update');
                    exit;
                } else {
                    $errors['general'] = 'Une erreur est survenue lors de la mise à jour du pilote';
                }
            }
            
            // En cas d'erreur, récupérer le pilote et afficher le formulaire à nouveau
            try {
                $pilote = $this->piloteModel->getPiloteById($id);
                include 'views/pilote/form_pilote.php';
            } catch (Exception $e) {
                header('Location: index.php?page=liste_pilote&error=update_error');
                exit;
            }
        } else {
            header('Location: index.php?page=modifier_pilote&id=' . $id);
            exit;
        }
    }
    
    public function delete($id) {
        // Vérifier si le pilote existe
        try {
            $pilote = $this->piloteModel->getPiloteById($id);
            
            if (!$pilote) {
                header('Location: index.php?page=liste_pilote&error=pilote_not_found');
                exit;
            }
            
            if ($this->piloteModel->deletePilote($id)) {
                header('Location: index.php?page=liste_pilote&success=delete');
            } else {
                header('Location: index.php?page=liste_pilote&error=delete');
            }
            exit;
        } catch (Exception $e) {
            header('Location: index.php?page=liste_pilote&error=delete_error');
            exit;
        }
    }
    
    public function resetPassword($id) {
        // Vérifier si le pilote existe
        try {
            $pilote = $this->piloteModel->getPiloteById($id);
            
            if (!$pilote) {
                header('Location: index.php?page=liste_pilote&error=pilote_not_found');
                exit;
            }
            
            // Réinitialiser le mot de passe
            $new_password = $this->piloteModel->resetPassword($id);
            
            if ($new_password) {
                // Dans une application réelle, on enverrait ce mot de passe par email
                // Pour l'exemple, on l'affiche à l'utilisateur
                header('Location: index.php?page=liste_pilote&success=reset&password=' . $new_password);
            } else {
                header('Location: index.php?page=liste_pilote&error=reset');
            }
            exit;
        } catch (Exception $e) {
            header('Location: index.php?page=liste_pilote&error=reset_error');
            exit;
        }
    }
} 