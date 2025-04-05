<?php
require_once 'models/Database.php';

class PiloteModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllPilotes() {
        try {
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE role = 'pilote' ORDER BY email");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function searchPilotes($keyword) {
        try {
            $keyword = "%$keyword%";
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs 
                                        WHERE role = 'pilote' 
                                        AND email LIKE :keyword
                                        ORDER BY email");
            $stmt->bindParam(':keyword', $keyword);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getPiloteById($id) {
        try {
            $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE id = :id AND role = 'pilote'");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result ? $result : false;
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function addPilote($email, $password) {
        try {
            // Hash du mot de passe
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("INSERT INTO utilisateurs (email, password, role) 
                                        VALUES (:email, :password, 'pilote')");
            $result = $stmt->execute([
                'email' => $email,
                'password' => $hashed_password
            ]);
            
            return $result;
        } catch (Exception $e) {
            // Gérer les erreurs (email unique, etc.)
            return false;
        }
    }
    
    public function updatePilote($id, $email) {
        try {
            $stmt = $this->db->prepare("UPDATE utilisateurs 
                                        SET email = :email
                                        WHERE id = :id AND role = 'pilote'");
            return $stmt->execute([
                'id' => $id,
                'email' => $email
            ]);
        } catch (Exception $e) {
            // Gérer les erreurs (email unique, etc.)
            return false;
        }
    }
    
    public function deletePilote($id) {
        try {
            // Vérifier si le pilote gère des entreprises ou des étudiants
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM pilote_entreprise WHERE pilote_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $entreprises_count = $stmt->fetchColumn();
            
            $stmt = $this->db->prepare("SELECT COUNT(*) FROM pilote_etudiant WHERE pilote_id = :id");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $etudiants_count = $stmt->fetchColumn();
            
            if ($entreprises_count > 0 || $etudiants_count > 0) {
                // Le pilote gère des entreprises ou des étudiants, impossible de le supprimer
                return false;
            }
            
            // Aucune relation, procéder à la suppression
            $stmt = $this->db->prepare("DELETE FROM utilisateurs WHERE id = :id AND role = 'pilote'");
            $stmt->bindParam(':id', $id);
            return $stmt->execute();
        } catch (Exception $e) {
            return false;
        }
    }
    
    public function resetPassword($id) {
        try {
            // Vérifier d'abord si le pilote existe
            $check = $this->db->prepare("SELECT id FROM utilisateurs WHERE id = :id AND role = 'pilote'");
            $check->bindParam(':id', $id);
            $check->execute();
            
            if (!$check->fetch()) {
                return false; // Le pilote n'existe pas
            }
            
            // Mot de passe par défaut
            $password = "Pilot2023"; // Mot de passe fixe au lieu d'un aléatoire
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            $stmt = $this->db->prepare("UPDATE utilisateurs SET password = :password WHERE id = :id AND role = 'pilote'");
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
    
    public function getEntreprisesByPilote($id) {
        try {
            $stmt = $this->db->prepare("SELECT e.* 
                                        FROM entreprises e
                                        JOIN pilote_entreprise pe ON e.id = pe.entreprise_id
                                        WHERE pe.pilote_id = :id
                                        ORDER BY e.nom");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    public function getEtudiantsByPilote($id) {
        try {
            $stmt = $this->db->prepare("SELECT u.* 
                                        FROM utilisateurs u
                                        JOIN pilote_etudiant pe ON u.id = pe.etudiant_id
                                        WHERE pe.pilote_id = :id AND u.role = 'etudiant'
                                        ORDER BY u.email");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Récupère les candidatures liées aux entreprises gérées par le pilote
     */
    public function getCandidaturesByPilote($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT c.*, o.titre, e.nom as nom_entreprise, u.email as email_etudiant, o.id as offre_id
                FROM candidatures c
                JOIN offres o ON c.offre_id = o.id
                JOIN entreprises e ON o.entreprise_id = e.id
                JOIN utilisateurs u ON c.utilisateur_id = u.id
                JOIN pilote_entreprise pe ON e.id = pe.entreprise_id
                WHERE pe.pilote_id = :id
                ORDER BY c.id DESC
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Récupère les notations d'entreprises faites par le pilote
     */
    public function getEntrepriseNotesByPilote($id) {
        try {
            $stmt = $this->db->prepare("
                SELECT n.*, e.nom as nom_entreprise, e.id as entreprise_id
                FROM notations n
                JOIN entreprises e ON n.entreprise_id = e.id
                WHERE n.utilisateur_id = :id
                ORDER BY n.date_notation DESC
            ");
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
} 