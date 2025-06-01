<?php
session_start();

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'client') {
    echo "<p>Vous devez être connecté pour voir vos réservations.</p>";
    exit;
}

$conn = new mysqli('localhost', 'root', '', 'LocationVoitures');
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

$sql = "SELECT r.*, v.marque, v.modele, v.carburant, v.prix_location 
        FROM Reservation r 
        JOIN Voiture v ON r.num_immatriculation = v.num_immatriculation 
        WHERE r.id_client = ? 
        ORDER BY r.date_debut DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $num_reservation = $row['num_reser'];
        $date_debut = $row['date_debut'];
        $date_fin = $row['date_fin'];
        $statut = $row['statut'];
        $tarif = $row['tarif'];
        $marque = $row['marque'];
        $modele = $row['modele'];
        $immatriculation = $row['num_immatriculation'];
        $img = 'images/' . $immatriculation . '.png';
        if (!file_exists($img)) $img = 'images/default_car_image.png';

        $statusClass = match($statut) {
            'confirmée' => 'status-confirmed',
            'en attente' => 'status-pending',
            'annulée' => 'status-cancelled',
            default => ''
        };

        $nb_jours = (new DateTime($date_debut))->diff(new DateTime($date_fin))->days;

        echo "
        <div class='reservation-card'>
            <div class='reservation-header'>
                <span class='reservation-status $statusClass'>$statut</span>
            </div>
            <div class='reservation-body'>
                <img src='$img' alt='$marque $modele' class='reservation-image'>
                <div class='reservation-details'>
                    <p><strong>Voiture:</strong> $marque $modele</p>
                    <p><strong>Immatriculation:</strong> $immatriculation</p>
                    <p><strong>Période:</strong> Du $date_debut au $date_fin ($nb_jours jours)</p>
                    <p><strong>Prix total:</strong> $tarif DH</p>
                </div>
            </div>";

        if ($statut === 'confirmée' && strtotime($date_debut) > time()) {
            echo "
            <div class='reservation-actions'>
                <button class='btn-cancel' onclick='cancelReservation($num_reservation)'>Annuler la réservation</button>
            </div>";
        }

        echo "</div>";
    }
} else {
    echo "<p>Vous n'avez aucune réservation pour le moment.</p>";
}

$conn->close();
?>

<!-- POPUP HTML -->
<div id="popup-message" class="popup hidden">
  <div class="popup-content">
    <p id="popup-text"></p>
    <button onclick="closePopup()">Fermer</button>
  </div>
</div>

<style>
  .popup {
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0, 0, 0, 0.4);
    display: flex; align-items: center; justify-content: center;
    z-index: 1000;
  }

  .popup-content {
    background: #fff;
    padding: 20px 30px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    text-align: center;
    max-width: 350px;
  }

  .popup-content button {
    margin-top: 15px;
    padding: 8px 18px;
    background: #00aaff;
    color: white;
    border: none;
    border-radius: 6px;
    cursor: pointer;
  }

  .hidden {
    display: none;
  }
</style>

<script>
function cancelReservation(reservationId) {
    if (confirm('Êtes-vous sûr de vouloir annuler cette réservation ?')) {
        fetch('cancel_reservation.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'reservation_id=' + reservationId
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showPopup(data.message);
            } else {
                showPopup('Erreur : ' + data.message);
            }
        })
        .catch(error => {
            console.error('Erreur:', error);
            showPopup('Une erreur est survenue.');
        });
    }
}

function showPopup(message) {
    document.getElementById('popup-text').innerText = message;
    document.getElementById('popup-message').classList.remove('hidden');
}

function closePopup() {
    document.getElementById('popup-message').classList.add('hidden');
    location.reload(); // recharge la liste
}
</script>
