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
$searchField = isset($_GET['field']) ? $_GET['field'] : null;
$searchValue = isset($_GET['value']) ? $_GET['value'] : null;

// Construire la requête SQL
$sql = "SELECT * FROM voiture";

if ($searchField && $searchValue) {
    // Sécuriser le champ de recherche (whitelist)
    $allowedFields = ['num_immatriculation', 'marque', 'modele', 'carburant', 'statut_voit', 'kilometrage', 'prix_location'];
    
    if (in_array($searchField, $allowedFields)) {
        // Pour les champs numériques
        if ($searchField === 'kilometrage' || $searchField === 'prix_location') {
            $sql .= " WHERE $searchField = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("d", $searchValue);
        } else {
            // Pour les champs texte
            $sql .= " WHERE $searchField LIKE ?";
            $searchValue = "%$searchValue%";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $searchValue);
        }
    } else {
        // Champ non autorisé
        $stmt = $conn->prepare($sql);
    }
} else {
    $stmt = $conn->prepare($sql);
}

$stmt->execute();
$result = $stmt->get_result();

// Générer le tableau HTML
echo '<table>
        <thead>
            <tr>
                <th>Immatriculation</th>
                <th>Marque</th>
                <th>Modèle</th>
                <th>Carburant</th>
                <th>Statut</th>
                <th>Kilométrage</th>
                <th>Prix/jour</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>';

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<tr>
                <td>' . htmlspecialchars($row['num_immatriculation']) . '</td>
                <td>' . htmlspecialchars($row['marque']) . '</td>
                <td>' . htmlspecialchars($row['modele']) . '</td>
                <td>' . htmlspecialchars($row['carburant']) . '</td>
                <td>' . htmlspecialchars($row['statut_voit']) . '</td>
                <td>' . htmlspecialchars($row['kilometrage']) . '</td>
                <td>' . htmlspecialchars($row['prix_location']) . ' dh</td>
                <td class="action-buttons">
                    <button class="edit-btn" data-id="' . htmlspecialchars($row['num_immatriculation']) . '">Modifier</button>
                    <button class="delete-btn" data-id="' . htmlspecialchars($row['num_immatriculation']) . '">Supprimer</button>
                </td>
            </tr>';
    }
} else {
    echo '<tr><td colspan="8" class="no-data">Aucune voiture trouvée</td></tr>';
}

echo '</tbody></table>';

$conn->close();
?>
