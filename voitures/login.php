<?php
session_start();

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    echo json_encode(["success" => false, "message" => "Erreur de connexion à la base de données."]);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $motdepasse = $_POST['password'] ?? '';

    // Vérification dans la table Client
    $stmtClient = $conn->prepare("SELECT * FROM Client WHERE email = ?");
    $stmtClient->bind_param("s", $email);
    $stmtClient->execute();
    $resultClient = $stmtClient->get_result();

    if ($resultClient->num_rows === 1) {
        $client = $resultClient->fetch_assoc();
        if ($client['motdepasse_client'] === $motdepasse) {
            $_SESSION['user_id'] = $client['id_client'];
            $_SESSION['role'] = 'client';
            echo json_encode(["success" => true, "poste" => "Client"]);
            exit;
        }
    }

    // Vérification dans la table Administrateur
    $stmtAdmin = $conn->prepare("SELECT * FROM Administrateur WHERE email_admin = ?");
    $stmtAdmin->bind_param("s", $email);
    $stmtAdmin->execute();
    $resultAdmin = $stmtAdmin->get_result();

    if ($resultAdmin->num_rows === 1) {
        $admin = $resultAdmin->fetch_assoc();
        if ($admin['motdepasse_admin'] === $motdepasse) {
            $_SESSION['user_id'] = $admin['id_admin'];
            $_SESSION['role'] = 'admin';
            echo json_encode(["success" => true, "poste" => "Administrateur"]);
            exit;
        }
    }

    echo json_encode(["success" => false, "message" => "Email ou mot de passe incorrect."]);
    exit;
}
?>
