<?php
// Fichier de diagnostic pour vérifier la connexion à la base de données

// Afficher toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Informations de connexion à la base de données
$host = 'localhost';
$db   = 'keke';
$user = 'root';
$pass = '';
$charset = 'utf8';

echo "<h1>Diagnostic de l'application</h1>";

// Vérifier la version de PHP
echo "<h2>Version de PHP</h2>";
echo "PHP Version: " . phpversion() . "<br>";
echo "Extensions chargées: " . implode(", ", get_loaded_extensions()) . "<br>";

// Vérifier les chemins
echo "<h2>Chemins de l'application</h2>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "Script Filename: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "Current Directory: " . getcwd() . "<br>";

// Vérifier si les fichiers controllers et models existent
echo "<h2>Vérification des fichiers</h2>";
$files_to_check = [
    'controllers/AuthController.php',
    'controllers/EntrepriseController.php',
    'models/EntrepriseModel.php',
    'models/Database.php',
    'views/entreprise/liste_entreprise.php',
    'views/entreprise/form_entreprise.php',
    'views/entreprise/details_entreprise.php'
];

foreach ($files_to_check as $file) {
    echo "Fichier $file: " . (file_exists($file) ? "Existe" : "N'existe pas") . "<br>";
}

// Tester la connexion à la base de données
echo "<h2>Test de connexion à la base de données</h2>";
try {
    $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];
    
    echo "Tentative de connexion à la base de données '$db'...<br>";
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connexion réussie!<br>";
    
    // Vérifier si les tables nécessaires existent
    echo "<h3>Vérification des tables</h3>";
    $tables = ['entreprises', 'notations', 'utilisateurs', 'offres_stage', 'candidatures'];
    
    foreach ($tables as $table) {
        $stmt = $pdo->query("SHOW TABLES LIKE '$table'");
        echo "Table $table: " . ($stmt->rowCount() > 0 ? "Existe" : "N'existe pas") . "<br>";
        
        if ($stmt->rowCount() > 0) {
            // Afficher la structure de la table
            $columns = $pdo->query("DESCRIBE $table")->fetchAll(PDO::FETCH_ASSOC);
            echo "<details><summary>Structure de $table</summary><pre>";
            print_r($columns);
            echo "</pre></details>";
            
            // Afficher le nombre d'enregistrements
            $count = $pdo->query("SELECT COUNT(*) FROM $table")->fetchColumn();
            echo "Nombre d'enregistrements dans $table: $count<br>";
        }
    }
    
} catch (PDOException $e) {
    echo "Erreur de connexion à la base de données: " . $e->getMessage() . "<br>";
}

echo "<h2>Session</h2>";
echo "Session status: " . session_status() . "<br>";
echo "Session variables: <pre>" . print_r($_SESSION, true) . "</pre>";

?> 