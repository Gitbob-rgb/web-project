<?php
require_once 'models/EntrepriseModel.php';

class EntrepriseController {
    private $entrepriseModel;
    
    public function __construct() {
        $this->entrepriseModel = new EntrepriseModel();
    }
    
    public function index() {
        $keyword = isset($_GET['search']) ? $_GET['search'] : '';
        
        if (!empty($keyword)) {
            $entreprises = $this->entrepriseModel->searchEntreprises($keyword);
        } else {
            $entreprises = $this->entrepriseModel->getAllEntreprises();
        }
        
        include 'views/entreprise/liste_entreprise.php';
    }
    
    public function show($id) {
        $entreprise = $this->entrepriseModel->getEntrepriseById($id);
        $notations = array();
        
        if (!$entreprise) {
            header('Location: index.php?page=liste_entreprise&error=entreprise_not_found');
            exit;
        }
        
        $notations = $this->entrepriseModel->getNotationsByEntreprise($id);
        
        include 'views/entreprise/details_entreprise.php';
    }
    
    public function createForm() {
        $errors = array();
        include 'views/entreprise/form_entreprise.php';
    }
    
    public function create() {
        $errors = array();
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            
            // Validation
            if (empty($nom)) {
                $errors['nom'] = 'Le nom de l\'entreprise est obligatoire';
            }
            
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email n\'est pas valide';
            }
            
            // Si pas d'erreurs, enregistrer
            if (empty($errors)) {
                if ($this->entrepriseModel->addEntreprise($nom, $adresse, $email)) {
                    header('Location: index.php?page=liste_entreprise&success=create');
                    exit;
                } else {
                    $error = 'Une erreur est survenue lors de l\'ajout de l\'entreprise';
                }
            }
            
            // En cas d'erreur, afficher le formulaire à nouveau
            include 'views/entreprise/form_entreprise.php';
        } else {
            header('Location: index.php?page=ajouter_entreprise');
            exit;
        }
    }
    
    public function editForm($id) {
        // Vérifier que l'utilisateur n'est pas un étudiant
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            header('Location: index.php?page=liste_entreprise&error=permission_denied');
            exit;
        }
        
        $errors = array();
        
        $entreprise = $this->entrepriseModel->getEntrepriseById($id);
        
        if (!$entreprise) {
            header('Location: index.php?page=liste_entreprise&error=entreprise_not_found');
            exit;
        }
        
        include 'views/entreprise/form_entreprise.php';
    }
    
    public function update($id) {
        // Vérifier que l'utilisateur n'est pas un étudiant
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            header('Location: index.php?page=liste_entreprise&error=permission_denied');
            exit;
        }
        
        $errors = array();
        $error = '';
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';
            $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
            $email = isset($_POST['email']) ? trim($_POST['email']) : '';
            
            // Validation
            if (empty($nom)) {
                $errors['nom'] = 'Le nom de l\'entreprise est obligatoire';
            }
            
            if (!empty($email) && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $errors['email'] = 'L\'email n\'est pas valide';
            }
            
            // Si pas d'erreurs, mettre à jour
            if (empty($errors)) {
                if ($this->entrepriseModel->updateEntreprise($id, $nom, $adresse, $email)) {
                    header('Location: index.php?page=liste_entreprise&success=update');
                    exit;
                } else {
                    $error = 'Une erreur est survenue lors de la mise à jour de l\'entreprise';
                }
            }
            
            // En cas d'erreur, récupérer l'entreprise et afficher le formulaire à nouveau
            $entreprise = $this->entrepriseModel->getEntrepriseById($id);
            include 'views/entreprise/form_entreprise.php';
        } else {
            header('Location: index.php?page=modifier_entreprise&id=' . $id);
            exit;
        }
    }
    
    public function delete($id) {
        // Vérifier que l'utilisateur n'est pas un étudiant
        if (isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'etudiant') {
            header('Location: index.php?page=liste_entreprise&error=permission_denied');
            exit;
        }
        
        // Vérifier si l'entreprise existe
        $entreprise = $this->entrepriseModel->getEntrepriseById($id);
        
        if (!$entreprise) {
            header('Location: index.php?page=liste_entreprise&error=entreprise_not_found');
            exit;
        }
        
        if ($this->entrepriseModel->deleteEntreprise($id)) {
            header('Location: index.php?page=liste_entreprise&success=delete');
        } else {
            header('Location: index.php?page=liste_entreprise&error=delete');
        }
        exit;
    }
    
    public function rate($id) {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $note = isset($_POST['note']) ? (int)$_POST['note'] : 0;
            
            // Validation
            if ($note < 1 || $note > 5) {
                header('Location: index.php?page=voir_entreprise&id=' . $id . '&error=invalid_rating');
                exit;
            }
            
            // Vérifier si l'utilisateur est connecté
            if (!isset($_SESSION['user_id'])) {
                header('Location: index.php?page=login');
                exit;
            }
            
            if ($this->entrepriseModel->addNotation($id, $_SESSION['user_id'], $note)) {
                header('Location: index.php?page=voir_entreprise&id=' . $id . '&success=rating');
            } else {
                header('Location: index.php?page=voir_entreprise&id=' . $id . '&error=rating');
            }
            exit;
        } else {
            header('Location: index.php?page=voir_entreprise&id=' . $id);
            exit;
        }
    }
}
?> 