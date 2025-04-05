<?php
require_once 'models/Database.php';

class EntrepriseModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function getAllEntreprises() {
        $query = "SELECT e.*, COUNT(DISTINCT o.id) as offre_count, COUNT(DISTINCT c.id) as candidature_count, 
                 IFNULL(AVG(n.note), 0) as moyenne_notation
                 FROM entreprises e
                 LEFT JOIN offres_stage o ON e.id = o.entreprise_id
                 LEFT JOIN candidatures c ON o.id = c.offre_stage_id
                 LEFT JOIN notations n ON e.id = n.entreprise_id
                 GROUP BY e.id
                 ORDER BY e.id DESC";
        
        $stmt = $this->db->query($query);
        return $stmt->fetchAll();
    }
    
    public function searchEntreprises($keyword) {
        $query = "SELECT e.*, COUNT(DISTINCT o.id) as offre_count, COUNT(DISTINCT c.id) as candidature_count, 
                 IFNULL(AVG(n.note), 0) as moyenne_notation
                 FROM entreprises e
                 LEFT JOIN offres_stage o ON e.id = o.entreprise_id
                 LEFT JOIN candidatures c ON o.id = c.offre_stage_id
                 LEFT JOIN notations n ON e.id = n.entreprise_id
                 WHERE e.nom LIKE :keyword OR e.adresse LIKE :keyword OR e.email LIKE :keyword
                 GROUP BY e.id
                 ORDER BY e.id DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['keyword' => "%$keyword%"]);
        return $stmt->fetchAll();
    }
    
    public function getEntrepriseById($id) {
        $query = "SELECT e.*, COUNT(DISTINCT o.id) as offre_count, COUNT(DISTINCT c.id) as candidature_count, 
                 IFNULL(AVG(n.note), 0) as moyenne_notation
                 FROM entreprises e
                 LEFT JOIN offres_stage o ON e.id = o.entreprise_id
                 LEFT JOIN candidatures c ON o.id = c.offre_stage_id
                 LEFT JOIN notations n ON e.id = n.entreprise_id
                 WHERE e.id = :id
                 GROUP BY e.id";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['id' => $id]);
        return $stmt->fetch();
    }
    
    public function getNotationsByEntreprise($entrepriseId) {
        $query = "SELECT n.*, u.email as user_email
                 FROM notations n
                 JOIN utilisateurs u ON n.utilisateur_id = u.id
                 WHERE n.entreprise_id = :entreprise_id
                 ORDER BY n.date_vote DESC";
        
        $stmt = $this->db->prepare($query);
        $stmt->execute(['entreprise_id' => $entrepriseId]);
        return $stmt->fetchAll();
    }
    
    public function addEntreprise($nom, $adresse, $email) {
        $stmt = $this->db->prepare("INSERT INTO entreprises (nom, adresse, email) VALUES (:nom, :adresse, :email)");
        return $stmt->execute([
            'nom' => $nom,
            'adresse' => $adresse,
            'email' => $email
        ]);
    }
    
    public function updateEntreprise($id, $nom, $adresse, $email) {
        $stmt = $this->db->prepare("UPDATE entreprises SET nom = :nom, adresse = :adresse, email = :email WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'nom' => $nom,
            'adresse' => $adresse,
            'email' => $email
        ]);
    }
    
    public function deleteEntreprise($id) {
        $stmt = $this->db->prepare("DELETE FROM entreprises WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
    
    public function addNotation($entrepriseId, $utilisateurId, $note) {
        // Vérifier si l'utilisateur a déjà noté cette entreprise
        $checkStmt = $this->db->prepare("SELECT id FROM notations WHERE entreprise_id = :entreprise_id AND utilisateur_id = :utilisateur_id");
        $checkStmt->execute([
            'entreprise_id' => $entrepriseId,
            'utilisateur_id' => $utilisateurId
        ]);
        
        if ($checkStmt->fetch()) {
            // Mettre à jour la note existante
            $stmt = $this->db->prepare("UPDATE notations SET note = :note, date_vote = NOW() WHERE entreprise_id = :entreprise_id AND utilisateur_id = :utilisateur_id");
        } else {
            // Ajouter une nouvelle note
            $stmt = $this->db->prepare("INSERT INTO notations (entreprise_id, utilisateur_id, note) VALUES (:entreprise_id, :utilisateur_id, :note)");
        }
        
        return $stmt->execute([
            'entreprise_id' => $entrepriseId,
            'utilisateur_id' => $utilisateurId,
            'note' => $note
        ]);
    }
}
?> 