<?php
require_once 'models/Database.php';

class OffreModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllOffres($page = 1, $perPage = 10) {
        $offset = ($page - 1) * $perPage;
        
        // Get total count
        $countQuery = "SELECT COUNT(*) as total FROM offres_stage";
        $countStmt = $this->db->query($countQuery);
        $totalCount = $countStmt->fetch()['total'];
        
        $query = "SELECT o.*, e.nom as entreprise_nom, 
                 (SELECT COUNT(*) FROM candidatures c WHERE c.offre_stage_id = o.id) as candidature_count
                 FROM offres_stage o
                 LEFT JOIN entreprises e ON o.entreprise_id = e.id
                 ORDER BY o.date_creation DESC
                 LIMIT :limit OFFSET :offset";
                 
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':limit', $perPage, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
        $stmt->execute();
        
        return [
            'data' => $stmt->fetchAll(),
            'total' => $totalCount,
            'pages' => ceil($totalCount / $perPage)
        ];
    }
    
    public function searchOffres($keyword) {
        $query = "SELECT o.*, e.nom as entreprise_nom, COUNT(c.id) as candidature_count
                 FROM offres_stage o
                 LEFT JOIN entreprises e ON o.entreprise_id = e.id
                 LEFT JOIN candidatures c ON o.id = c.offre_stage_id
                 WHERE o.titre LIKE :keyword OR o.description LIKE :keyword OR e.nom LIKE :keyword
                 GROUP BY o.id
                 ORDER BY o.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
    
    public function getOffreById($id) {
        $query = "SELECT o.*, e.nom as entreprise_nom, COUNT(c.id) as candidature_count
                 FROM offres_stage o
                 LEFT JOIN entreprises e ON o.entreprise_id = e.id
                 LEFT JOIN candidatures c ON o.id = c.offre_stage_id
                 WHERE o.id = :id
                 GROUP BY o.id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getCandidaturesByOffre($offreId) {
        $query = "SELECT c.*, u.email as user_email
                 FROM candidatures c
                 JOIN utilisateurs u ON c.utilisateur_id = u.id
                 WHERE c.offre_stage_id = :offre_id
                 ORDER BY c.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['offre_id' => $offreId]);
        return $stmt->fetchAll();
    }
    
    public function addOffre($titre, $description, $entreprise_id, $date_debut, $date_fin, $specialite) {
        $stmt = $this->db->prepare("INSERT INTO offres_stage (titre, description, entreprise_id, date_debut, date_fin, specialite, date_creation) 
                                    VALUES (:titre, :description, :entreprise_id, :date_debut, :date_fin, :specialite, NOW())");
        return $stmt->execute([
            'titre' => $titre,
            'description' => $description,
            'entreprise_id' => $entreprise_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'specialite' => $specialite
        ]);
    }
    
    public function updateOffre($id, $titre, $description, $entreprise_id, $date_debut, $date_fin, $specialite) {
        $stmt = $this->db->prepare("UPDATE offres_stage 
                                   SET titre = :titre, 
                                       description = :description, 
                                       entreprise_id = :entreprise_id,
                                       date_debut = :date_debut,
                                       date_fin = :date_fin,
                                       specialite = :specialite
                                   WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'titre' => $titre,
            'description' => $description,
            'entreprise_id' => $entreprise_id,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'specialite' => $specialite
        ]);
    }
    
    public function deleteOffre($id) {
        // Supprimer d'abord les candidatures liées à cette offre
        $stmtCandidatures = $this->db->prepare("DELETE FROM candidatures WHERE offre_stage_id = :id");
        $stmtCandidatures->execute(['id' => $id]);
        
        // Puis supprimer l'offre
        $stmt = $this->db->prepare("DELETE FROM offres_stage WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function hasPostule($offreId, $utilisateurId) {
        $stmt = $this->db->prepare("SELECT id FROM candidatures WHERE offre_stage_id = :offre_id AND utilisateur_id = :utilisateur_id");
        $stmt->execute([
            'offre_id' => $offreId,
            'utilisateur_id' => $utilisateurId
        ]);
        
        return $stmt->rowCount() > 0;
    }
    
    public function addCandidature($offreId, $utilisateurId) {
        $stmt = $this->db->prepare("INSERT INTO candidatures (offre_stage_id, utilisateur_id, statut) 
                                    VALUES (:offre_id, :utilisateur_id, 'en attente')");
        return $stmt->execute([
            'offre_id' => $offreId,
            'utilisateur_id' => $utilisateurId
        ]);
    }
    
    public function addCandidatureComplete($offreId, $utilisateurId, $nom, $prenom, $cv_path, $lettre_path) {
        $stmt = $this->db->prepare("INSERT INTO candidatures (offre_stage_id, utilisateur_id, nom, prenom, cv_path, lettre_path, statut) 
                                    VALUES (:offre_id, :utilisateur_id, :nom, :prenom, :cv_path, :lettre_path, 'en attente')");
        return $stmt->execute([
            'offre_id' => $offreId,
            'utilisateur_id' => $utilisateurId,
            'nom' => $nom,
            'prenom' => $prenom,
            'cv_path' => $cv_path,
            'lettre_path' => $lettre_path
        ]);
    }
    
    /**
     * Vérifie si une offre est déjà dans la wishlist d'un étudiant
     */
    public function isInWishlist($offreId, $utilisateurId) {
        $stmt = $this->db->prepare("SELECT id FROM wish_list WHERE offre_stage_id = :offre_id AND utilisateur_id = :utilisateur_id");
        $stmt->execute([
            'offre_id' => $offreId,
            'utilisateur_id' => $utilisateurId
        ]);
        
        return $stmt->rowCount() > 0;
    }
    
    /**
     * Ajoute une offre à la wishlist d'un étudiant
     */
    public function addToWishlist($offreId, $utilisateurId) {
        $stmt = $this->db->prepare("INSERT INTO wish_list (offre_stage_id, utilisateur_id, date_ajout) 
                                    VALUES (:offre_id, :utilisateur_id, NOW())");
        return $stmt->execute([
            'offre_id' => $offreId,
            'utilisateur_id' => $utilisateurId
        ]);
    }
    
    /**
     * Récupère toutes les offres dans la wishlist d'un utilisateur
     */
    public function getWishlistByUserId($utilisateurId) {
        try {
            $stmt = $this->db->prepare("
                SELECT w.*, o.titre, o.specialite, o.date_debut, o.date_fin, w.offre_stage_id as offre_id, 
                       e.nom as entreprise_nom, w.date_ajout
                FROM wish_list w
                JOIN offres_stage o ON w.offre_stage_id = o.id
                JOIN entreprises e ON o.entreprise_id = e.id
                WHERE w.utilisateur_id = :utilisateur_id
                ORDER BY w.date_ajout DESC
            ");
            $stmt->bindParam(':utilisateur_id', $utilisateurId);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (Exception $e) {
            return [];
        }
    }
    
    /**
     * Supprime une offre de la wishlist d'un utilisateur
     */
    public function removeFromWishlist($offreId, $utilisateurId) {
        try {
            $stmt = $this->db->prepare("DELETE FROM wish_list 
                                      WHERE offre_stage_id = :offre_id 
                                      AND utilisateur_id = :utilisateur_id");
            return $stmt->execute([
                'offre_id' => $offreId,
                'utilisateur_id' => $utilisateurId
            ]);
        } catch (Exception $e) {
            return false;
        }
    }
}
?>