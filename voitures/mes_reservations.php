<?php
session_start();
$pdo = new PDO("mysql:host=localhost;dbname=LocationVoitures", "root", "");

$id_client = $_SESSION['id_client'];

$sql = "SELECT r.*, v.marque, v.modele, v.num_immatriculation
        FROM Reservation r
        JOIN Voiture v ON r.num_immatriculation = v.num_immatriculation
        WHERE r.id_client = ?
        ORDER BY r.date_debut DESC";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id_client]);
$reservations = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Mes Réservations</title>
  <link rel="stylesheet" href="styless.css">
</head>
<body>
<h2>Mes Réservations</h2>
<table border="1">
  <tr>
    <th>Voiture</th>
    <th>Immatriculation</th>
    <th>Du</th>
    <th>Au</th>
    <th>Tarif</th>
    <th>Statut</th>
  </tr>
  <?php foreach ($reservations as $res): ?>
    <tr>
      <td><?= $res['marque'] . ' ' . $res['modele'] ?></td>
      <td><?= $res['num_immatriculation'] ?></td>
      <td><?= $res['date_debut'] ?></td>
      <td><?= $res['date_fin'] ?></td>
      <td><?= $res['tarif'] ?> DH</td>
      <td><?= $res['statut'] ?></td>
    </tr>
  <?php endforeach; ?>
</table>
</body>
</html>
