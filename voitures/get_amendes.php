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

// Vérifier s'il y a une recherche
$searchValue = isset($_GET['search']) ? $_GET['search'] : null;

// Construire la requête SQL


$sql = "SELECT a.*, r.num_reser, c.nom_client, c.prenom_client, v.num_immatriculation
        FROM amende a
        JOIN reservation r ON a.num_reser = r.num_reser
        JOIN client c ON r.id_client = c.id_client
        JOIN voiture v ON r.num_immatriculation = v.num_immatriculation";


if ($searchValue) {
    $sql .= " WHERE c.nom_client LIKE ?";
    $searchValue = "%$searchValue%";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $searchValue);
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Générer le tableau HTML
echo '<table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>N° Réservation</th>
                <th>Client</th>
                <th>Immatriculation</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['id_amende']) . '</td>
                <td>' . htmlspecialchars($row['type_amende']) . '</td>
                <td>' . htmlspecialchars($row['description']) . '</td>
                <td>' . htmlspecialchars($row['montant']) . ' dh</td>
                <td>' . htmlspecialchars($row['num_reser']) . '</td>
                <td>' . htmlspecialchars($row['nom_client'] . ' ' . $row['prenom_client']) . '</td>
                <td>' . htmlspecialchars($row['num_immatriculation']) . '</td>
                <td>
                    <button class="delete-amende-btn" data-id="' . htmlspecialchars($row['id_amende']) . '">Supprimer</button>
                </td>
            </tr>';
    }
} else {
    echo '<tr><td colspan="8" class="no-data">Aucune amende trouvée</td></tr>';
}

echo '</tbody></table>';

$conn->close();
?>
