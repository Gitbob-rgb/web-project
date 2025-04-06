<?php
require_once 'models/Database.php';

class UserModel {
    private $db;
    
    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }
    
    public function verifyCredentials($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Pour les anciens mots de passe hachés avec bcrypt
            if (strpos($user['motdepasse'], '$2y$') === 0) {
                return password_verify($password, $user['motdepasse']);
            } else {
                // Pour les mots de passe non hachés (pour la démo)
                return $user['motdepasse'] === $password;
            }
        }
        
        return false;
    }
    
    public function getUserByEmail($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->execute(['email' => $email]);
        return $stmt->fetch();
    }
    
    public function getAllUsers() {
        $stmt = $this->db->query("SELECT * FROM utilisateurs ORDER BY id DESC");
        return $stmt->fetchAll();
    }
    
    public function getUsersByRole($role) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateurs WHERE role = :role ORDER BY id DESC");
        $stmt->execute(['role' => $role]);
        return $stmt->fetchAll();
    }
    
    public function addUser($email, $password, $role) {
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $this->db->prepare("INSERT INTO utilisateurs (email, motdepasse, role) VALUES (:email, :password, :role)");
        return $stmt->execute([
            'email' => $email,
            'password' => $hashedPassword,
            'role' => $role
        ]);
    }
    
    public function updateUser($id, $email, $role) {
        $stmt = $this->db->prepare("UPDATE utilisateurs SET email = :email, role = :role WHERE id = :id");
        return $stmt->execute([
            'id' => $id,
            'email' => $email,
            'role' => $role
        ]);
    }
    
    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM utilisateurs WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }
}
?> 