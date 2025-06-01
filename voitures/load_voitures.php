<?php
session_start();
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
  die("Erreur de connexion: " . $conn->connect_error);
}

$sql = "SELECT * FROM Voiture ";
$result = $conn->query($sql);
?>

<div class="gallery">
<?php
if ($result->num_rows > 0) {
  while ($row = $result->fetch_assoc()) {
      $id = htmlspecialchars($row['num_immatriculation']);
      $marque = htmlspecialchars($row['marque']);
      $modele = htmlspecialchars($row['modele']);
      $carburant = htmlspecialchars($row['carburant']);
      $kilometrage = htmlspecialchars($row['kilometrage']);
      $prix = htmlspecialchars($row['prix_location']);
      $img = 'images/' . $id . '.png';

      // Vérifier si l'image existe côté serveur
      if (!file_exists($img)) {
          $img = 'images/default_car_image.png';
      }
      
      echo "
          <div class='car-item'>
              <img src='$img' alt='Voiture $id' class='car-image' 
                   onclick=\"openModal('$id', '$img', '$marque', '$modele', '$carburant', '$kilometrage', '$prix')\"
                   onerror=\"this.src='images/default_car_image.png'\">
          </div>";
  }
} else {
  echo "<p>Aucune voiture disponible actuellement.</p>";
}
?>
</div>

<?php $conn->close(); ?>