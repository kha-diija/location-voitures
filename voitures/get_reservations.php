<?php
session_start();

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('HTTP/1.1 403 Forbidden');
    exit('Accès refusé');
}

// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

// Requête SQL pour récupérer les réservations avec les informations du client et de la voiture
$sql = "SELECT r.*, c.nom_client, c.prenom_client, v.marque, v.modele, 
        DATEDIFF(r.date_fin, r.date_debut) as nb_jours
        FROM reservation r
        JOIN client c ON r.id_client = c.id_client
        JOIN voiture v ON r.num_immatriculation = v.num_immatriculation
        ORDER BY r.date_debut DESC";

$result = $conn->query($sql);

// Générer le tableau HTML
echo '<table>
        <thead>
            <tr>
                <th>N° Réservation</th>
                <th>Client</th>
                <th>Voiture</th>
                <th>Date début</th>
                <th>Date fin</th>
                <th>Nb jours</th>
                <th>Tarif/jour</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['num_reser']) . '</td>
                <td>' . htmlspecialchars($row['nom_client'] . ' ' . $row['prenom_client']) . '</td>
                <td>' . htmlspecialchars($row['marque'] . ' ' . $row['modele'] . ' (' . $row['num_immatriculation'] . ')') . '</td>
                <td>' . htmlspecialchars($row['date_debut']) . '</td>
                <td>' . htmlspecialchars($row['date_fin']) . '</td>
                <td>' . htmlspecialchars($row['nb_jours']) . '</td>
                <td>' . htmlspecialchars($row['tarif']) . ' dh</td>
                <td>' . htmlspecialchars($row['statut']) . '</td>
            </tr>';
    }
} else {
    echo '<tr><td colspan="8" class="no-data">Aucune réservation trouvée</td></tr>';
}

echo '</tbody></table>';

$conn->close();
?>
