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

// Requête SQL pour récupérer les clients
$sql = "SELECT * FROM client ORDER BY nom_client, prenom_client";
$result = $conn->query($sql);

// Générer le tableau HTML
echo '<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Nom</th>
                <th>Prénom</th>
                <th>Téléphone</th>
                <th>Adresse</th>
                <th>N° Permis</th>
                <th>Date Permis</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['id_client']) . '</td>
                <td>' . htmlspecialchars($row['nom_client']) . '</td>
                <td>' . htmlspecialchars($row['prenom_client']) . '</td>
                <td>' . htmlspecialchars($row['num_tel']) . '</td>
                <td>' . htmlspecialchars($row['adresse']) . '</td>
                <td>' . htmlspecialchars($row['num_permis']) . '</td>
                <td>' . htmlspecialchars($row['date_permis']) . '</td>
                <td>' . htmlspecialchars($row['email']) . '</td>
            </tr>';
    }
} else {
    echo '<tr><td colspan="8" class="no-data">Aucun client trouvé</td></tr>';
}

echo '</tbody></table>';

$conn->close();
?>
