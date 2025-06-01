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

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Vérifier si l'utilisateur est connecté
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        ?>
        <script>
            window.onload = function() {
                showCustomAlert("Vous devez être connecté pour effectuer une réservation.", function() {
                    window.location.href = 'login.html';
                });
            };
        </script>
        <?php
        include('custom_alert.php'); // Inclure le fichier avec le HTML et CSS pour l'alerte
        exit;
    }

    $num_immatriculation = $_POST['num_immatriculation'] ?? '';
    $date_debut = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? '';
    $user_id = $_SESSION['user_id'];
    $role = $_SESSION['role'];

    // Validation des dates comme demandé
    if ($date_debut >= $date_fin) {
        ?>
        <script>
            window.onload = function() {
                showCustomAlert("La date de début doit être antérieure à la date de fin.", function() {
                    window.history.back();
                });
            };
        </script>
        <?php
        include('custom_alert.php');
        exit;
    }

    // Vérifier si la voiture existe
    $stmt = $conn->prepare("SELECT * FROM Voiture WHERE num_immatriculation = ?");
    $stmt->bind_param("s", $num_immatriculation);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        ?>
        <script>
            window.onload = function() {
                showCustomAlert("Voiture non trouvée.", function() {
                    window.history.back();
                });
            };
        </script>
        <?php
        include('custom_alert.php');
        exit;
    }

    $voiture = $result->fetch_assoc();
    $prix_location = $voiture['prix_location'];

    // Récupérer les informations du client
    $stmt = $conn->prepare("SELECT * FROM Client WHERE id_client = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $resultClient = $stmt->get_result();
    
    if ($resultClient->num_rows === 0) {
        ?>
        <script>
            window.onload = function() {
                showCustomAlert("Informations client non trouvées.", function() {
                    window.history.back();
                });
            };
        </script>
        <?php
        include('custom_alert.php');
        exit;
    }
    
    $client = $resultClient->fetch_assoc();

    // Vérifier si la voiture est disponible pour les dates sélectionnées
    $stmt = $conn->prepare("
        SELECT * FROM Reservation 
        WHERE num_immatriculation = ? 
        AND ((date_debut BETWEEN ? AND ?) 
        OR (date_fin BETWEEN ? AND ?) 
        OR (date_debut <= ? AND date_fin >= ?))
    ");
    $stmt->bind_param("sssssss", 
        $num_immatriculation, 
        $date_debut, $date_fin, 
        $date_debut, $date_fin, 
        $date_debut, $date_fin
    );
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // La voiture n'est pas disponible pour ces dates
        ?>
        <script>
            window.onload = function() {
                showCustomAlert("La voiture n'est pas disponible pour les dates sélectionnées.", function() {
                    window.history.back();
                });
            };
        </script>
        <?php
        include('custom_alert.php');
        exit;
    } else {
        // La voiture est disponible, calculer le prix total
        $date1 = new DateTime($date_debut);
        $date2 = new DateTime($date_fin);
        $interval = $date1->diff($date2);
        $nb_jours = $interval->days;
        $prix_total = $prix_location * $nb_jours;

        // Stocker les informations de réservation dans la session pour les utiliser plus tard
        $_SESSION['reservation_temp'] = [
            'num_immatriculation' => $num_immatriculation,
            'date_debut' => $date_debut,
            'date_fin' => $date_fin,
            'prix_total' => $prix_total,
            'nb_jours' => $nb_jours,
            'voiture' => $voiture,
            'client' => $client
        ];

        // Afficher le formulaire de paiement avec les informations du client
        ?>
        <!DOCTYPE html>
        <html lang="fr">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Paiement de la réservation</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    margin: 0;
                    padding: 20px;
                    background-color: #f0f8ff;
                }
                .container {
                    max-width: 800px;
                    margin: 0 auto;
                    background: white;
                    padding: 20px;
                    border-radius: 10px;
                    box-shadow: 0 0 20px rgba(0,150,199,0.2);
                }
                h1, h2 {
                    color: #0096c7;
                    text-align: center;
                }
                .details-section {
                    display: flex;
                    justify-content: space-between;
                    margin-bottom: 20px;
                }
                .client-details, .reservation-details {
                    background-color: #f9f9f9;
                    padding: 15px;
                    border-radius: 8px;
                    width: 48%;
                    box-shadow: 0 2px 5px rgba(0,0,0,0.1);
                }
                .form-group {
                    margin-bottom: 15px;
                }
                label {
                    display: block;
                    margin-bottom: 5px;
                    font-weight: bold;
                    color: #0096c7;
                }
                input[type="text"],
                input[type="number"] {
                    width: 100%;
                    padding: 10px;
                    border: 1px solid #ddd;
                    border-radius: 5px;
                    box-sizing: border-box;
                    transition: border-color 0.3s;
                }
                input[type="text"]:focus,
                input[type="number"]:focus {
                    border-color: #44f6ff;
                    outline: none;
                    box-shadow: 0 0 5px rgba(68,246,255,0.5);
                }
                .expiry-cvv {
                    display: flex;
                    gap: 15px;
                }
                .expiry-cvv div {
                    flex: 1;
                }
                button {
                    background-color: #44f6ff;
                    color: white;
                    padding: 12px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-size: 16px;
                    width: 100%;
                    font-weight: bold;
                    transition: background-color 0.3s;
                }
                button:hover {
                    background-color: #65edff;
                }
                @media (max-width: 768px) {
                    .details-section {
                        flex-direction: column;
                    }
                    .client-details, .reservation-details {
                        width: 100%;
                        margin-bottom: 15px;
                    }
                }
                
                /* Style pour les alertes personnalisées */
                .custom-alert {
                    display: none;
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    width: 300px;
                    background-color: white;
                    border-radius: 10px;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
                    z-index: 1002;
                    overflow: hidden;
                }
                .alert-content {
                    padding: 20px;
                    text-align: center;
                }
                .alert-content p {
                    margin-bottom: 20px;
                    font-size: 16px;
                    color: #333;
                }
                .alert-content button {
                    background-color: #44f6ff;
                    color: white;
                    padding: 10px 20px;
                    border: none;
                    border-radius: 5px;
                    cursor: pointer;
                    font-weight: bold;
                    width: auto;
                }
                .overlay {
                    display: none;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    height: 100%;
                    background-color: rgba(0, 0, 0, 0.7);
                    z-index: 1001;
                }
            </style>
            <script>
                function validateForm() {
                    const cardNumber = document.getElementById('numero_carte').value;
                    const expiry = document.getElementById('date_expiration').value;
                    const cvv = document.getElementById('cvv').value;
                    
                    // Validation simple du numéro de carte (16 chiffres)
                    if (!/^\d{16}$/.test(cardNumber.replace(/\s/g, ''))) {
                        showCustomAlert("Veuillez entrer un numéro de carte valide (16 chiffres)");
                        return false;
                    }
                    
                    // Validation de la date d'expiration (format MM/AA)
                    if (!/^(0[1-9]|1[0-2])\/([0-9]{2})$/.test(expiry)) {
                        showCustomAlert("Veuillez entrer une date d'expiration valide (MM/AA)");
                        return false;
                    }
                    
                    // Validation du CVV (3 ou 4 chiffres)
                    if (!/^\d{3,4}$/.test(cvv)) {
                        showCustomAlert("Veuillez entrer un CVV valide (3 ou 4 chiffres)");
                        return false;
                    }
                    
                    return true;
                }
                
                function showCustomAlert(message, callback) {
                    document.getElementById('alert-message').textContent = message;
                    document.getElementById('custom-alert').style.display = 'block';
                    document.getElementById('overlay').style.display = 'block';
                    
                    // Si un callback est fourni, l'ajouter au bouton OK
                    if (callback) {
                        document.getElementById('alert-ok').onclick = function() {
                            closeAlert();
                            callback();
                        };
                    }
                }
                
                function closeAlert() {
                    document.getElementById('custom-alert').style.display = 'none';
                    document.getElementById('overlay').style.display = 'none';
                }
            </script>
        </head>
        <body>
            <!-- Overlay pour le fond sombre -->
            <div id="overlay" class="overlay"></div>
            
            <!-- Container pour les popups d'alerte -->
            <div id="custom-alert" class="custom-alert">
                <div class="alert-content">
                    <p id="alert-message"></p>
                    <button id="alert-ok" onclick="closeAlert()">OK</button>
                </div>
            </div>
            
            <div class="container">
                <h1>Finaliser votre réservation</h1>
                
                <div class="details-section">
                    <div class="client-details">
                        <h2>Informations client</h2>
                        <p><strong>Nom:</strong> <?php echo $client['nom_client']; ?></p>
                        <p><strong>Prénom:</strong> <?php echo $client['prenom_client']; ?></p>
                        <p><strong>Téléphone:</strong> <?php echo $client['num_tel']; ?></p>
                        <p><strong>Adresse:</strong> <?php echo $client['adresse']; ?></p>
                        <p><strong>Email:</strong> <?php echo $client['email']; ?></p>
                        <p><strong>Numéro de permis:</strong> <?php echo $client['num_permis']; ?></p>
                        <p><strong>Date du permis:</strong> <?php echo $client['date_permis']; ?></p>
                    </div>
                    
                    <div class="reservation-details">
                        <h2>Détails de la réservation</h2>
                        <p><strong>Voiture:</strong> <?php echo $voiture['marque'] . ' ' . $voiture['modele']; ?></p>
                        <p><strong>Immatriculation:</strong> <?php echo $num_immatriculation; ?></p>
                        <p><strong>Date de début:</strong> <?php echo $date_debut; ?></p>
                        <p><strong>Date de fin:</strong> <?php echo $date_fin; ?></p>
                        <p><strong>Nombre de jours:</strong> <?php echo $nb_jours; ?></p>
                        <p><strong>Prix par jour:</strong> <?php echo $prix_location; ?> €</p>
                        <p><strong>Prix total:</strong> <?php echo $prix_total; ?> €</p>
                    </div>
                </div>
                
                <form action="confirmation.php" method="POST" onsubmit="return validateForm()">
                    <h2>Informations de paiement</h2>
                    <div class="form-group">
                        <label for="nom_carte">Nom sur la carte</label>
                        <input type="text" id="nom_carte" name="nom_carte" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="numero_carte">Numéro de carte</label>
                        <input type="text" id="numero_carte" name="numero_carte" placeholder="XXXX XXXX XXXX XXXX" required>
                    </div>
                    
                    <div class="form-group expiry-cvv">
                        <div>
                            <label for="date_expiration">Date d'expiration</label>
                            <input type="text" id="date_expiration" name="date_expiration" placeholder="MM/AA" required>
                        </div>
                        <div>
                            <label for="cvv">CVV</label>
                            <input type="number" id="cvv" name="cvv" placeholder="123" required>
                        </div>
                    </div>
                    
                    <button type="submit">Confirmer et payer</button>
                </form>
            </div>
        </body>
        </html>
        <?php
    }
} else {
    // Si la méthode n'est pas POST, rediriger vers la page d'accueil
    header('Location: index.php');
    exit;
}
?>