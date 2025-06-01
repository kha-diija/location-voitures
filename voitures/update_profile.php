<?php
session_start();
header('Content-Type: application/json');

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    echo json_encode(['success' => false, 'message' => 'Vous devez être connecté pour modifier votre profil.']);
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Erreur de connexion à la base de données.']);
    exit;
}

$user_id = $_SESSION['user_id'];
$nom = $_POST['nom'] ?? '';
$prenom = $_POST['prenom'] ?? '';
$email = $_POST['email'] ?? '';
$telephone = $_POST['telephone'] ?? '';
$adresse = $_POST['adresse'] ?? '';
$num_permis = $_POST['num_permis'] ?? '';
$date_permis = $_POST['date_permis'] ?? '';
$new_password = $_POST['password'] ?? '';
$confirm_password = $_POST['confirm_password'] ?? '';

// Vérifier si l'email existe déjà pour un autre utilisateur
$stmt = $conn->prepare("SELECT id_client FROM Client WHERE email = ? AND id_client != ?");
$stmt->bind_param("si", $email, $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    echo json_encode(['success' => false, 'message' => 'Cet email est déjà utilisé par un autre compte.']);
    exit;
}

// Si le mot de passe est vide : mise à jour sans le changer
if (empty($new_password)) {
    $sql = "UPDATE Client SET nom_client = ?, prenom_client = ?, email = ?, num_tel = ?, adresse = ?, num_permis = ?, date_permis = ? WHERE id_client = ?";
    $params = [$nom, $prenom, $email, $telephone, $adresse, $num_permis, $date_permis, $user_id];
    $types = "sssssssi";
} else {
    // Vérifier que les deux mots de passe correspondent
    if ($new_password !== $confirm_password) {
        echo json_encode(['success' => false, 'message' => 'Les mots de passe ne correspondent pas.']);
        exit;
    }

    // Mise à jour avec mot de passe
    $sql = "UPDATE Client SET nom_client = ?, prenom_client = ?, email = ?, num_tel = ?, adresse = ?, num_permis = ?, date_permis = ?, motdepasse_client = ? WHERE id_client = ?";
    $params = [$nom, $prenom, $email, $telephone, $adresse, $num_permis, $date_permis, $new_password, $user_id];
    $types = "ssssssssi";
}

// Préparer et exécuter la requête
$stmt = $conn->prepare($sql);
$stmt->bind_param($types, ...$params);

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'message' => 'Profil mis à jour avec succès.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour du profil: ' . $conn->error]);
}

$conn->close();
?>
