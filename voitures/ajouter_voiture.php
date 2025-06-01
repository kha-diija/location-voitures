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
    $num_immatriculation = $_POST['num_immatriculation'];
    $marque = $_POST['marque'];
    $modele = $_POST['modele'];
    $carburant = $_POST['carburant'];
    $statut_voit = $_POST['statut_voit'];
    $kilometrage = $_POST['kilometrage'];
    $prix_location = $_POST['prix_location'];

    // Vérifier si la voiture existe déjà
    $check_sql = "SELECT * FROM voiture WHERE num_immatriculation = ?";
    $check_stmt = $conn->prepare($check_sql);
    $check_stmt->bind_param("s", $num_immatriculation);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // La voiture existe déjà
        $_SESSION['popup'] = [
            'message' => 'Une voiture avec ce numéro d\'immatriculation existe déjà.',
            'type' => 'error'
        ];
        header('Location: admin.php');
        exit;
    } else {
        // Insérer la nouvelle voiture
        $insert_sql = "INSERT INTO voiture (num_immatriculation, marque, modele, carburant, statut_voit, kilometrage, prix_location) 
                      VALUES (?, ?, ?, ?, ?, ?, ?)";
        $insert_stmt = $conn->prepare($insert_sql);
        $insert_stmt->bind_param("sssssid", $num_immatriculation, $marque, $modele, $carburant, $statut_voit, $kilometrage, $prix_location);

        if ($insert_stmt->execute()) {
            $_SESSION['popup'] = [
                'message' => 'Voiture ajoutée avec succès.',
                'type' => 'success'
            ];
            header('Location: admin.php');
            exit;
        } else {
            $_SESSION['popup'] = [
                'message' => 'Erreur lors de l\'ajout de la voiture: ' . $insert_stmt->error,
                'type' => 'error'
            ];
            header('Location: admin.php');
            exit;
        }
    }

    $conn->close();
} else {
    // Rediriger si le formulaire n'a pas été soumis
    header('Location: admin.php');
    exit;
}
?>
