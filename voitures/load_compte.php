<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    echo "<p>Vous devez être connecté pour voir votre profil.</p>";
    exit;
}

$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$conn = new mysqli($host, $user, $password, $database);
if ($conn->connect_error) {
    die("Erreur de connexion: " . $conn->connect_error);
}

$user_id = $_SESSION['user_id'];

// Récupérer les informations du client
$sql = "SELECT * FROM Client WHERE id_client = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $client = $result->fetch_assoc();
    
    echo "
    <div class='profile-container'>
        <div class='profile-header'>
            <h3>Informations personnelles</h3>
            <p>Vous pouvez modifier vos informations ci-dessous</p>
        </div>
        
        <form id='profile-form' onsubmit='updateProfile(event)'>
            <div class='profile-form'>
                <div class='form-group'>
                    <label for='nom'>Nom</label>
                    <input type='text' id='nom' name='nom' value='" . htmlspecialchars($client['nom_client']) . "' required>
                </div>
                
                <div class='form-group'>
                    <label for='prenom'>Prénom</label>
                    <input type='text' id='prenom' name='prenom' value='" . htmlspecialchars($client['prenom_client']) . "' required>
                </div>
                
                <div class='form-group'>
                    <label for='email'>Email</label>
                    <input type='email' id='email' name='email' value='" . htmlspecialchars($client['email']) . "' required>
                </div>
                
                <div class='form-group'>
                    <label for='telephone'>Téléphone</label>
                    <input type='tel' id='telephone' name='telephone' value='" . htmlspecialchars($client['num_tel']) . "' required>
                </div>
                
                <div class='form-group'>
                    <label for='adresse'>Adresse</label>
                    <input type='text' id='adresse' name='adresse' value='" . htmlspecialchars($client['adresse']) . "' required>
                </div>
                
                <div class='form-group'>
                    <label for='num_permis'>Numéro de permis</label>
                    <input type='text' id='num_permis' name='num_permis' value='" . htmlspecialchars($client['num_permis']) . "' required>
                </div>
                
                <div class='form-group'>
                    <label for='date_permis'>Date d'obtention du permis</label>
                    <input type='date' id='date_permis' name='date_permis' value='" . htmlspecialchars($client['date_permis']) . "' disabled>
                </div>
                
                <div class='form-group'>
                    <label for='password'>Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type='password' id='password' name='password'>
                </div>
                
                <div class='form-group'>
                    <label for='confirm_password'>Confirmer le nouveau mot de passe</label>
                    <input type='password' id='confirm_password' name='confirm_password'>
                </div>
                
                <div class='form-actions'>
                    <button type='submit' class='btn-update'>Mettre à jour mon profil</button>
                </div>
            </div>
        </form>
    </div>";
} else {
    echo "<p>Impossible de récupérer vos informations. Veuillez vous reconnecter.</p>";
}

$conn->close();
?>