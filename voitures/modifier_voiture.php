<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit(json_encode(['success' => false, 'message' => 'Accès refusé']));
}

// Vérifier si la requête est de type POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('HTTP/1.1 405 Method Not Allowed');
    exit(json_encode(['success' => false, 'message' => 'Méthode non autorisée']));
}

// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    header('HTTP/1.1 500 Internal Server Error');
    exit(json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données']));
}

// Récupérer les données du formulaire
$num_immatriculation = $_POST['num_immatriculation'];
$marque = $_POST['marque'];
$modele = $_POST['modele'];
$carburant = $_POST['carburant'];
$statut_voit = $_POST['statut_voit'];
$kilometrage = $_POST['kilometrage'];
$prix_location = $_POST['prix_location'];

// Mettre à jour la voiture
$sql = "UPDATE voiture SET marque = ?, modele = ?, carburant = ?, statut_voit = ?, kilometrage = ?, prix_location = ? 
        WHERE num_immatriculation = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssssids", $marque, $modele, $carburant, $statut_voit, $kilometrage, $prix_location, $num_immatriculation);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour: ' . $stmt->error]);
}

$conn->close();
?>
