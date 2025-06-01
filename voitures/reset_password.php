<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Récupération des données du formulaire
    $email = $_POST['email'] ?? '';
    $oldPassword = $_POST['old_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    // Vérifications de base
    if (empty($email) || empty($oldPassword) || empty($newPassword) || empty($confirmPassword)) {
        exit("Tous les champs sont obligatoires.");
    }

    // Vérifier si les nouveaux mots de passe correspondent
    if ($newPassword !== $confirmPassword) {
        exit("Les mots de passe ne correspondent pas.");
    }

    // Rechercher le client avec l'email
    $stmt = $pdo->prepare("SELECT motdepasse_client FROM Client WHERE email = :email");
    $stmt->execute(['email' => $email]);
    $client = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$client) {
        exit("Client introuvable.");
    }

    // Vérifier l'ancien mot de passe (sans hashage, comme demandé)
    if ($client['motdepasse_client'] !== $oldPassword) {
        exit("Ancien mot de passe incorrect.");
    }

    // Mise à jour du mot de passe
    $update = $pdo->prepare("UPDATE Client SET motdepasse_client = :new_password WHERE email = :email");
    $update->execute(['new_password' => $newPassword, 'email' => $email]);

    // Redirection après succès avec message
    echo "<script>
        alert('Mot de passe modifié avec succès !');
        window.location.href = 'login.php';
    </script>";
    exit();

} catch (PDOException $e) {
    exit("Erreur de base de données : " . $e->getMessage());
}
?>
