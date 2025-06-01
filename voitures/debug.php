<?php
// Fichier de débogage pour tester les requêtes
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Enregistrer les requêtes dans un fichier de log
file_put_contents('debug_log.txt', date('Y-m-d H:i:s') . " - Requête reçue\n", FILE_APPEND);

// Enregistrer les données POST
if (!empty($_POST)) {
    file_put_contents('debug_log.txt', "Données POST: " . print_r($_POST, true) . "\n", FILE_APPEND);
}

// Simuler une réponse JSON valide
header('Content-Type: application/json');
echo json_encode([
    'success' => true,
    'message' => 'Test de débogage réussi',
    'received_data' => $_POST
]);
?>
