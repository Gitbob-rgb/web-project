<?php
// Afficher toutes les erreurs
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<h1>Test des chemins</h1>";

// Vérifier les chemins du serveur
echo "<h2>Chemins du serveur</h2>";
echo "Document Root: " . $_SERVER['DOCUMENT_ROOT'] . "<br>";
echo "PHP_SELF: " . $_SERVER['PHP_SELF'] . "<br>";
echo "REQUEST_URI: " . $_SERVER['REQUEST_URI'] . "<br>";
echo "SCRIPT_NAME: " . $_SERVER['SCRIPT_NAME'] . "<br>";
echo "SCRIPT_FILENAME: " . $_SERVER['SCRIPT_FILENAME'] . "<br>";
echo "PATH_INFO: " . (isset($_SERVER['PATH_INFO']) ? $_SERVER['PATH_INFO'] : 'Non défini') . "<br>";
echo "Chemin absolu actuel: " . __DIR__ . "<br>";
echo "Nom du fichier actuel: " . __FILE__ . "<br>";

// Tester les chemins relatifs
echo "<h2>Test des chemins relatifs</h2>";

$relative_paths = [
    "index.php",
    "./index.php",
    "../index.php",
    "../../index.php",
    "controllers/EntrepriseController.php",
    "./controllers/EntrepriseController.php",
    "../controllers/EntrepriseController.php",
    "models/Database.php",
    "./models/Database.php",
    "../models/Database.php"
];

foreach ($relative_paths as $path) {
    echo "Chemin: $path - " . (file_exists($path) ? "EXISTE" : "N'EXISTE PAS") . "<br>";
}

// Tester les URLs
echo "<h2>Test des URLs</h2>";

$base_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$relative_urls = [
    "index.php?page=liste_entreprise",
    "./index.php?page=liste_entreprise",
    "../index.php?page=liste_entreprise",
    "../../index.php?page=liste_entreprise"
];

foreach ($relative_urls as $url) {
    $full_url = $base_url . '/' . $url;
    echo "URL: <a href='$url'>$url</a> (URL complète: $full_url)<br>";
}

// Créer une fonction de correction des liens
echo "<h2>Suggestion de correction des liens</h2>";

function correct_path($path) {
    $script_dir = dirname($_SERVER['SCRIPT_FILENAME']);
    $document_root = $_SERVER['DOCUMENT_ROOT'];
    $relative_to_root = str_replace($document_root, '', $script_dir);
    
    // Déterminer combien de niveaux nous sommes en dessous de la racine
    $levels = substr_count($relative_to_root, '/');
    
    // Construire le chemin vers la racine
    $path_to_root = str_repeat('../', $levels);
    
    return $path_to_root . ltrim($path, './');
}

$links_to_fix = [
    "index.php?page=liste_entreprise",
    "index.php?page=ajouter_entreprise",
    "index.php?page=modifier_entreprise&id=1",
    "index.php?page=supprimer_entreprise&id=1"
];

foreach ($links_to_fix as $link) {
    echo "Lien original: $link<br>";
    echo "Lien corrigé: " . correct_path($link) . "<br><br>";
}
?> 