<?php
session_start();

// Connexion à la base de données
$conn = new mysqli('localhost', 'root', '', 'LocationVoitures');
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupération et nettoyage des données du formulaire
    $nom = trim($_POST['nom_client']);
    $prenom = trim($_POST['prenom_client']);
    $tel = trim($_POST['num_tel']);
    $adresse = trim($_POST['adresse']);
    $permis = trim($_POST['num_permis']);
    $date_permis = $_POST['date_permis'];
    $email = trim($_POST['email']);
    $motdepasse = $_POST['motdepasse_client'];

    // Vérifier si le permis a au moins 2 ans
    $dateActuelle = new DateTime();
    $datePermis = new DateTime($date_permis);
    $diff = $datePermis->diff($dateActuelle)->y;

    if ($diff < 2) {
        header("Location: signup.php?popup=permis");
        exit();
    }

    // Vérifier si l'email existe déjà
    $stmt = $conn->prepare("SELECT id_client FROM Client WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        header("Location: signup.php?popup=email");
        exit();
    }

   
    // Insertion dans la base de données
    $insert = $conn->prepare("INSERT INTO Client (nom_client, prenom_client, num_tel, adresse, num_permis, date_permis, email, motdepasse_client)
                              VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $insert->bind_param("ssssssss", $nom, $prenom, $tel, $adresse, $permis, $date_permis, $email, $motdepasse);

    if ($insert->execute()) {
      header("Location: signup.php?popup=succes");
      exit();
  }
   else {
      header("Location: signup.php?popup=erreur");
      exit();
  }
  
}
?>
