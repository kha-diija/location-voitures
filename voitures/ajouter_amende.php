<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Connexion à la base de données
    $host = 'localhost';
    $user = 'root';
    $password = '';
    $database = 'LocationVoitures';

    $conn = new mysqli($host, $user, $password, $database);
    if ($conn->connect_error) {
        die("Erreur de connexion: " . $conn->connect_error);
    }

    // Récupérer les données du formulaire
    $type_amende = $_POST['type_amende'];
    $description = $_POST['description'];
    $montant = $_POST['montant'];
    $num_reser = $_POST['num_reser'];

    // Insérer la nouvelle amende
    $sql = "INSERT INTO amende (type_amende, description, montant, num_reser) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssdi", $type_amende, $description, $montant, $num_reser);

    if ($stmt->execute()) {
        $_SESSION['popup'] = [
            'message' => 'Amende ajoutée avec succès.',
            'type' => 'success'
        ];
        header('Location: admin.php');
        exit;
    } else {
        $_SESSION['popup'] = [
            'message' => 'Erreur lors de l\'ajout de l\'amende: ' . $stmt->error,
            'type' => 'error'
        ];
        header('Location: admin.php');
        exit;
    }

    $conn->close();
} else {
    // Rediriger si le formulaire n'a pas été soumis
    header('Location: admin.php');
    exit;
}
?>
