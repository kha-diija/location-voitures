<?php
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$sql = "SELECT * FROM Voiture WHERE statut_voit = 'disponible'";
$result = $conn->query($sql);
?>