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

// Vérifier si l'ID est fourni
if (!isset($_POST['id'])) {
    header('HTTP/1.1 400 Bad Request');
    exit(json_encode(['success' => false, 'message' => 'ID non fourni']));
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

// Vérifier si la voiture est utilisée dans des réservations
$id = $_POST['id'];
$check_sql = "SELECT COUNT(*) as count FROM reservation WHERE num_immatriculation = ?";
$check_stmt = $conn->prepare($check_sql);
$check_stmt->bind_param("s", $id);
$check_stmt->execute();
$check_result = $check_stmt->get_result();
$row = $check_result->fetch_assoc();

if ($row['count'] > 0) {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Impossible de supprimer cette voiture car elle est utilisée dans des réservations.']);
    exit;
}

// Supprimer la voiture
$sql = "DELETE FROM voiture WHERE num_immatriculation = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $id);

if ($stmt->execute()) {
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
} else {
    header('HTTP/1.1 500 Internal Server Error');
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la suppression: ' . $stmt->error]);
}

$conn->close();
?>
