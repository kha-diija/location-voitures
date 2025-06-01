<?php
session_start();

// Vérifier si l'utilisateur est connecté et si les informations de réservation sont disponibles
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || !isset($_SESSION['reservation_temp'])) {
    echo "<script>
        alert('Informations de réservation non disponibles.');
        window.location.href = 'index.php';
    </script>";
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

// Récupérer les informations de réservation
$reservation = $_SESSION['reservation_temp'];
$num_immatriculation = $reservation['num_immatriculation'];
$date_debut = $reservation['date_debut'];
$date_fin = $reservation['date_fin'];
$prix_total = $reservation['prix_total'];
$nb_jours = $reservation['nb_jours'];
$voiture = $reservation['voiture'];
$client = $reservation['client'];
$user_id = $_SESSION['user_id'];

// Dans un environnement réel, vous traiteriez ici le paiement avec un service comme Stripe ou PayPal
// Pour cet exemple, nous supposons que le paiement est toujours réussi

// Enregistrer la réservation dans la base de données
$stmt = $conn->prepare("INSERT INTO Reservation (date_debut, date_fin, statut, tarif, id_client, num_immatriculation) VALUES (?, ?, 'confirmée', ?, ?, ?)");
$stmt->bind_param("ssdis", $date_debut, $date_fin, $prix_total, $user_id, $num_immatriculation);

if ($stmt->execute()) {
    // Récupérer l'ID de la réservation
    $num_reser = $conn->insert_id;
    
    // Générer un numéro de facture unique
    $num_facture = 'F-' . date('Ymd') . '-' . $num_reser;
    
    // Date actuelle pour la facture
    $date_facture = date('Y-m-d');
    
    // Supprimer les informations temporaires de réservation
    unset($_SESSION['reservation_temp']);
    
    // Afficher la facture avec option de téléchargement
    ?>
    <!DOCTYPE html>
    <html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Réservation confirmée - Facture</title>
        <style>
            body {
                font-family: Arial, sans-serif;
                line-height: 1.6;
                margin: 0;
                padding: 20px;
                background-color: #f4f4f4;
            }
            .container {
                max-width: 800px;
                margin: 0 auto;
                background: white;
                padding: 20px;
                border-radius: 5px;
                box-shadow: 0 0 10px rgba(0,0,0,0.1);
            }
            .success-message {
                text-align: center;
                margin-bottom: 30px;
            }
            .success-icon {
                color: #4CAF50;
                font-size: 48px;
                margin-bottom: 20px;
            }
            h1, h2 {
                color: #333;
            }
            .facture {
                border: 1px solid #ddd;
                padding: 20px;
                margin-bottom: 20px;
            }
            .facture-header {
                display: flex;
                justify-content: space-between;
                border-bottom: 2px solid #333;
                padding-bottom: 10px;
                margin-bottom: 20px;
            }
            .facture-body {
                margin-bottom: 20px;
            }
            .details-section {
                display: flex;
                justify-content: space-between;
                margin-bottom: 20px;
            }
            .client-details, .reservation-details {
                width: 48%;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-bottom: 20px;
            }
            table, th, td {
                border: 1px solid #ddd;
            }
            th, td {
                padding: 10px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            .total-row {
                font-weight: bold;
            }
            .buttons {
                display: flex;
                justify-content: space-between;
                margin-top: 20px;
            }
            .button {
                display: inline-block;
                background-color: #4CAF50;
                color: white;
                padding: 10px 15px;
                text-decoration: none;
                border-radius: 4px;
                text-align: center;
                cursor: pointer;
            }
            .button.print {
                background-color: #2196F3;
            }
            .button.download {
                background-color: #FF9800;
            }
            @media print {
                body {
                    background-color: white;
                    padding: 0;
                }
                .container {
                    box-shadow: none;
                    max-width: 100%;
                }
                .success-message, .buttons {
                    display: none;
                }
            }
            @media (max-width: 768px) {
                .details-section {
                    flex-direction: column;
                }
                .client-details, .reservation-details {
                    width: 100%;
                    margin-bottom: 15px;
                }
                .buttons {
                    flex-direction: column;
                    gap: 10px;
                }
            }
        </style>
        <script>
            function printFacture() {
                window.print();
            }
            
            function downloadFacture() {
                // Créer un élément invisible pour contenir le contenu HTML
                const element = document.createElement('a');
                const facture = document.querySelector('.facture').outerHTML;
                
                // Créer un blob avec le contenu HTML
                const blob = new Blob([`
                    <html>
                    <head>
                        <title>Facture <?php echo $num_facture; ?></title>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .facture { padding: 20px; }
                            .facture-header { display: flex; justify-content: space-between; border-bottom: 2px solid #333; padding-bottom: 10px; margin-bottom: 20px; }
                            .details-section { display: flex; justify-content: space-between; margin-bottom: 20px; }
                            .client-details, .reservation-details { width: 48%; }
                            table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
                            table, th, td { border: 1px solid #ddd; }
                            th, td { padding: 10px; text-align: left; }
                            th { background-color: #f2f2f2; }
                            .total-row { font-weight: bold; }
                        </style>
                    </head>
                    <body>
                        ${facture}
                    </body>
                    </html>
                `], { type: 'text/html' });
                
                // Créer une URL pour le blob
                const url = URL.createObjectURL(blob);
                
                // Configurer l'élément pour le téléchargement
                element.href = url;
                element.download = 'Facture_<?php echo $num_facture; ?>.html';
                
                // Simuler un clic pour déclencher le téléchargement
                document.body.appendChild(element);
                element.click();
                
                // Nettoyer
                document.body.removeChild(element);
                URL.revokeObjectURL(url);
            }
        </script>
    </head>
    <body>
        <div class="container">
            <div class="success-message">
                <div class="success-icon">✓</div>
                <h1>Réservation confirmée !</h1>
                <p>Votre réservation a été enregistrée avec succès et votre paiement a été traité.</p>
            </div>
            
            <div class="facture" id="facture">
                <div class="facture-header">
                    <div>
                        <h2>FACTURE</h2>
                        <p>LocationVoitures</p>
                        <p>123 Rue de la Location</p>
                        <p>75000 Paris, France</p>
                        <p>Tél: 01 23 45 67 89</p>
                        <p>Email: contact@locationvoitures.fr</p>
                    </div>
                    <div>
                        <p><strong>Facture N°:</strong> <?php echo $num_facture; ?></p>
                        <p><strong>Date:</strong> <?php echo $date_facture; ?></p>
                        <p><strong>Réservation N°:</strong> <?php echo $num_reser; ?></p>
                    </div>
                </div>
                
                <div class="facture-body">
                    <div class="details-section">
                        <div class="client-details">
                            <h3>Client</h3>
                            <p><strong>Nom:</strong> <?php echo $client['nom_client']; ?></p>
                            <p><strong>Prénom:</strong> <?php echo $client['prenom_client']; ?></p>
                            <p><strong>Adresse:</strong> <?php echo $client['adresse']; ?></p>
                            <p><strong>Téléphone:</strong> <?php echo $client['num_tel']; ?></p>
                            <p><strong>Email:</strong> <?php echo $client['email']; ?></p>
                        </div>
                        
                        <div class="reservation-details">
                            <h3>Détails de la réservation</h3>
                            <p><strong>Voiture:</strong> <?php echo $voiture['marque'] . ' ' . $voiture['modele']; ?></p>
                            <p><strong>Immatriculation:</strong> <?php echo $num_immatriculation; ?></p>
                            <p><strong>Date de début:</strong> <?php echo $date_debut; ?></p>
                            <p><strong>Date de fin:</strong> <?php echo $date_fin; ?></p>
                            <p><strong>Nombre de jours:</strong> <?php echo $nb_jours; ?></p>
                        </div>
                    </div>
                    
                    <table>
                        <thead>
                            <tr>
                                <th>Description</th>
                                <th>Prix unitaire</th>
                                <th>Quantité</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Location <?php echo $voiture['marque'] . ' ' . $voiture['modele']; ?></td>
                                <td><?php echo $voiture['prix_location']; ?> €/jour</td>
                                <td><?php echo $nb_jours; ?> jours</td>
                                <td><?php echo $prix_total; ?> €</td>
                            </tr>
                            <tr class="total-row">
                                <td colspan="3" style="text-align: right;"><strong>Total</strong></td>
                                <td><strong><?php echo $prix_total; ?> €</strong></td>
                            </tr>
                        </tbody>
                    </table>
                    
                    <div>
                        <h3>Conditions de location</h3>
                        <p>1. Le véhicule doit être rendu dans l'état où il a été loué.</p>
                        <p>2. Tout dommage sera facturé selon notre grille tarifaire.</p>
                        <p>3. En cas de retard, des frais supplémentaires seront appliqués.</p>
                        <p>4. Le carburant n'est pas inclus dans le prix de la location.</p>
                    </div>
                </div>
            </div>
            
            <div class="buttons">
                <a href="client.php" class="button">Retour </a>
                <button onclick="printFacture()" class="button print">Imprimer la facture</button>
                <button onclick="downloadFacture()" class="button download">Télécharger la facture</button>
            </div>
        </div>
    </body>
    </html>
    <?php
} else {
    // Erreur lors de l'enregistrement de la réservation
    echo "<script>alert('Erreur lors de l\'enregistrement de la réservation: " . $conn->error . "'); window.history.back();</script>";
}
?>