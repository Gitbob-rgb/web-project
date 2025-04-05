<?php
require_once 'models/Database.php';

class EtudiantModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllEtudiants() {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE role = 'etudiant' ORDER BY email");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function searchEtudiants($keyword) {
        $keyword = "%$keyword%";
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs 
                                    WHERE role = 'etudiant' 
                                    AND email LIKE :keyword
                                    ORDER BY email");
        $stmt->bindParam(':keyword', $keyword);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEtudiantById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = :id AND role = 'etudiant'");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function addEtudiant($email, $password) {
        // Hash du mot de passe
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        try {
            $stmt = $this->db->prepare("INSERT INTO utilisateurs (email, password, role) 
                                        VALUES (:email, :password, 'etudiant')");
            $result = $stmt->execute([
                'email' => $email,
                'password' => $hashed_password
            ]);
            
            return $result;
        } catch (PDOException $e) {
            // Gérer les erreurs (email unique, etc.)
            return false;
        }
    }
    
    public function updateEtudiant($id, $email) {
        try {
            $stmt = $this->db->prepare("UPDATE utilisateurs 
                                        SET email = :email
                                        WHERE id = :id AND role = 'etudiant'");
            return $stmt->execute([
                'id' => $id,
                'email' => $email
            ]);
        } catch (PDOException $e) {
            // Gérer les erreurs (email unique, etc.)
            return false;
        }
    }
    
    public function deleteEtudiant($id) {
        // Vérifier d'abord si l'étudiant a des candidatures
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM candidatures WHERE utilisateur_id = :id");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $candidatures_count = $stmt->fetchColumn();
        
        if ($candidatures_count > 0) {
            // L'étudiant a des candidatures, impossible de le supprimer
            return false;
        }
        
        // Aucune candidature, procéder à la suppression
        $stmt = $this->db->prepare("DELETE FROM utilisateurs WHERE id = :id AND role = 'etudiant'");
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
    
    public function resetPassword($id) {
        try {
            // Vérifier d'abord si l'étudiant existe
            $check = $this->db->prepare("SELECT id FROM utilisateurs WHERE id = :id AND role = 'etudiant'");
            $check->bindParam(':id', $id);
            $check->execute();
            
            if (!$check->fetch()) {
                return false; // L'étudiant n'existe pas
            }
            
            // Mot de passe par défaut
            $password = "Student2023"; // Mot de passe fixe au lieu d'un aléatoire
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("UPDATE utilisateurs SET password = :password WHERE id = :id AND role = 'etudiant'");
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->bindParam(':password', $hashed_password);
            $result = $stmt->execute();
            
            if ($result) {
                return $password;
            }
            
            return false;
        } catch (Exception $e) {
            // En cas d'erreur, afficher le message d'erreur et retourner false
            error_log("Erreur lors de la réinitialisation du mot de passe: " . $e->getMessage());
            return false;
        }
    }
    
    public function getCandidaturesByEtudiant($id) {
        $stmt = $this->db->prepare("SELECT c.*, o.titre AS offre_titre, e.nom AS entreprise_nom 
                                    FROM candidatures c
                                    JOIN offres_stage o ON c.offre_stage_id = o.id
                                    JOIN entreprises e ON o.entreprise_id = e.id
                                    WHERE c.utilisateur_id = :id
                                    ORDER BY c.id DESC");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getWishlistByEtudiant($id) {
        $stmt = $this->db->prepare("SELECT w.*, o.titre AS offre_titre, e.nom AS entreprise_nom 
                                    FROM wish_list w
                                    JOIN offres_stage o ON w.offre_stage_id = o.id
                                    JOIN entreprises e ON o.entreprise_id = e.id
                                    WHERE w.utilisateur_id = :id
                                    ORDER BY w.date_ajout DESC");
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function getEntrepriseNotesByEtudiant($id) {
        try {
            $stmt = $this->db->prepare("SELECT n.*, e.nom AS entreprise_nom, n.note, n.date_notation
                                        FROM notations n
                                        JOIN entreprises e ON n.entreprise_id = e.id
                                        WHERE n.utilisateur_id = :id
                                        ORDER BY n.date_notation DESC");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
} 