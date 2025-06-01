<?php
session_start();

// Vérifier s'il y a un message popup à afficher
$popup = null;
if (isset($_SESSION['popup'])) {
    $popup = $_SESSION['popup'];
    unset($_SESSION['popup']);
}

// Vérifier si l'utilisateur est connecté et est un administrateur
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit;
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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Location de Voitures</title>
    <link rel="stylesheet" href="admin_styles.css">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <?php if ($popup): ?>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const popup = document.getElementById('popup');
            popup.textContent = <?php echo json_encode($popup['message']); ?>;
            popup.className = "popup <?php echo $popup['type']; ?>";
            popup.style.display = 'block';
            
            setTimeout(() => {
                popup.style.display = 'none';
            }, 3500);
        });
    </script>
    <?php endif; ?>
</head>
<body>
    <div class="dashboard">
        <div class="sidebar">
            <div class="logo">
                <h2>CAR RENTAL</h2>
                <p>Administration</p>
            </div>
            <nav>
                <button class="nav-button" id="voitureBtn">Voiture</button>
                <div class="sub-menu" id="voitureSubMenu">
                    <button class="sub-button" id="ajouterVoitureBtn">Ajouter Voiture</button>
                    <button class="sub-button" id="afficherVoitureBtn">Afficher Voiture</button>
                </div>
                
                <button class="nav-button" id="reservationBtn">Réservation</button>
                <button class="nav-button" id="utilisateurBtn">Utilisateur</button>
                <button class="nav-button" id="amendeBtn">Amende</button>
            </nav>
            <div class="logout">
                <a href="logout.php">Déconnexion</a>
            </div>
        </div>
        
        <div class="content">
            <div class="header">
                <h1>Tableau de bord</h1>
                <div class="user-info">
                    <?php
                    // Récupérer les informations de l'administrateur connecté
                    $admin_id = $_SESSION['user_id'];
                    $query = "SELECT nom_admin, prenom_admin FROM administrateur WHERE id_admin = ?";
                    $stmt = $conn->prepare($query);
                    $stmt->bind_param("i", $admin_id);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    
                    if ($row = $result->fetch_assoc()) {
                        echo "<p>Bienvenue, " . $row['prenom_admin'] . " " . $row['nom_admin'] . "</p>";
                    }
                    ?>
                </div>
            </div>
            
            <div class="main-content">
                <!-- Contenu dynamique sera chargé ici -->
                <div id="defaultContent" class="content-section">
                    <h2>Bienvenue dans le panneau d'administration</h2>
                    <p>Utilisez le menu à gauche pour gérer les voitures, réservations, utilisateurs et amendes.</p>
                    
                    <div class="stats-container">
                        <?php
                        // Statistiques des voitures
                        $query = "SELECT COUNT(*) as total FROM voiture";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalVoitures = $row['total'];
                        
                        // Statistiques des réservations
                        $query = "SELECT COUNT(*) as total FROM reservation";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalReservations = $row['total'];
                        
                        // Statistiques des clients
                        $query = "SELECT COUNT(*) as total FROM client";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalClients = $row['total'];
                        
                        // Statistiques des amendes
                        $query = "SELECT COUNT(*) as total FROM amende";
                        $result = $conn->query($query);
                        $row = $result->fetch_assoc();
                        $totalAmendes = $row['total'];
                        ?>
                        
                        <div class="stat-card">
                            <h3>Voitures</h3>
                            <p class="stat-number"><?php echo $totalVoitures; ?></p>
                        </div>
                        
                        <div class="stat-card">
                            <h3>Réservations</h3>
                            <p class="stat-number"><?php echo $totalReservations; ?></p>
                        </div>
                        
                        <div class="stat-card">
                            <h3>Clients</h3>
                            <p class="stat-number"><?php echo $totalClients; ?></p>
                        </div>
                        
                        <div class="stat-card">
                            <h3>Amendes</h3>
                            <p class="stat-number"><?php echo $totalAmendes; ?></p>
                        </div>
                    </div>
                </div>
                
                <!-- Section Ajouter Voiture -->
                <div id="ajouterVoitureContent" class="content-section" style="display: none;">
                    <h2>Ajouter une nouvelle voiture</h2>
                    <form id="ajouterVoitureForm" method="post" action="ajouter_voiture.php">
                        <div class="form-group">
                            <label for="num_immatriculation">Numéro d'immatriculation</label>
                            <input type="text" id="num_immatriculation" name="num_immatriculation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="marque">Marque</label>
                            <input type="text" id="marque" name="marque" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="modele">Modèle</label>
                            <input type="text" id="modele" name="modele" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="carburant">Carburant</label>
                            <select id="carburant" name="carburant" required>
                                <option value="Essence">Essence</option>
                                <option value="Diesel">Diesel</option>
                                <option value="Électrique">Électrique</option>
                                <option value="Hybride">Hybride</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="statut_voit">Statut</label>
                            <select id="statut_voit" name="statut_voit" required>
                                <option value="disponible">Disponible</option>
                                <option value="réservée">Réservée</option>
                                <option value="en maintenance">En maintenance</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="kilometrage">Kilométrage</label>
                            <input type="number" id="kilometrage" name="kilometrage" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="prix_location">Prix de location (par jour)</label>
                            <input type="number" id="prix_location" name="prix_location" step="0.01" required>
                        </div>
                        
                        <button type="submit" class="submit-btn">Ajouter la voiture</button>
                    </form>
                </div>
                
                <!-- Section Afficher Voiture -->
                <div id="afficherVoitureContent" class="content-section" style="display: none;">
                    <h2>Liste des voitures</h2>
                    
                    <div class="search-container">
                        <select id="searchField">
                            <option value="num_immatriculation">Numéro d'immatriculation</option>
                            <option value="marque">Marque</option>
                            <option value="modele">Modèle</option>
                            <option value="carburant">Carburant</option>
                            <option value="kilometrage">Kilométrage</option>
                            <option value="prix_location">Prix de location</option>
                        </select>
                        <input type="text" id="searchInput" placeholder="Rechercher...">
                        <button id="searchButton">Rechercher</button>
                    </div>
                    
                    <div class="table-container" id="voitureTableContainer">
                        <!-- Le tableau des voitures sera chargé ici via AJAX -->
                    </div>
                </div>
                
                <!-- Section Réservations -->
                <div id="reservationContent" class="content-section" style="display: none;">
                    <h2>Liste des réservations</h2>
                    <div class="table-container" id="reservationTableContainer">
                        <!-- Le tableau des réservations sera chargé ici via AJAX -->
                    </div>
                </div>
                
                <!-- Section Utilisateurs -->
                <div id="utilisateurContent" class="content-section" style="display: none;">
                    <h2>Liste des clients</h2>
                    <div class="table-container" id="clientTableContainer">
                        <!-- Le tableau des clients sera chargé ici via AJAX -->
                    </div>
                </div>
                
                <!-- Section Amendes -->
                <div id="amendeContent" class="content-section" style="display: none;">
                    <h2>Gestion des amendes</h2>
                    
                    <div class="amende-container">
                        <div class="amende-form">
                            <h3>Ajouter une amende</h3>
                            <form id="ajouterAmendeForm" method="post" action="ajouter_amende.php">
                                <div class="form-group">
                                    <label for="type_amende">Type d'amende</label>
                                    <input type="text" id="type_amende" name="type_amende" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="description">Description</label>
                                    <textarea id="description" name="description" required></textarea>
                                </div>
                                
                                <div class="form-group">
                                    <label for="montant">Montant</label>
                                    <input type="number" id="montant" name="montant" step="0.01" required>
                                </div>
                                
                                <div class="form-group">
                                    <label for="num_reser">Numéro de réservation</label>
                                    <select id="num_reser" name="num_reser" required>
                                        <option value="">Sélectionner une réservation</option>
                                        <?php
                                        $query = "SELECT r.num_reser, c.nom_client, c.prenom_client, v.num_immatriculation 
                                                 FROM reservation r 
                                                 JOIN client c ON r.id_client = c.id_client 
                                                 JOIN voiture v ON r.num_immatriculation = v.num_immatriculation";
                                        $result = $conn->query($query);
                                        
                                        while ($row = $result->fetch_assoc()) {
                                            echo "<option value='" . $row['num_reser'] . "'>" . 
                                                 $row['num_reser'] . " - " . $row['nom_client'] . " " . $row['prenom_client'] . 
                                                 " (" . $row['num_immatriculation'] . ")</option>";
                                        }
                                        ?>
                                    </select>
                                </div>
                                
                                <button type="submit" class="submit-btn">Ajouter l'amende</button>
                            </form>
                        </div>
                        
                        <div class="amende-list">
                            <h3>Liste des amendes</h3>
                            
                            <div class="search-container">
                                <input type="text" id="searchAmendeInput" placeholder="Rechercher par nom de client...">
                                <button id="searchAmendeButton">Rechercher</button>
                            </div>
                            
                            <div class="table-container" id="amendeTableContainer">
                                <!-- Le tableau des amendes sera chargé ici via AJAX -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Modal pour modifier une voiture -->
    <div id="modifierVoitureModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Modifier la voiture</h2>
                <span class="close">&times;</span>
            </div>
            <form id="modifierVoitureForm">
                <input type="hidden" id="edit_num_immatriculation" name="num_immatriculation">
                
                <div class="form-group">
                    <label for="edit_marque">Marque</label>
                    <input type="text" id="edit_marque" name="marque" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_modele">Modèle</label>
                    <input type="text" id="edit_modele" name="modele" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_carburant">Carburant</label>
                    <select id="edit_carburant" name="carburant" required>
                        <option value="Essence">Essence</option>
                        <option value="Diesel">Diesel</option>
                        <option value="Électrique">Électrique</option>
                        <option value="Hybride">Hybride</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_statut_voit">Statut</label>
                    <select id="edit_statut_voit" name="statut_voit" required>
                        <option value="disponible">Disponible</option>
                        <option value="réservée">Réservée</option>
                        <option value="en maintenance">En maintenance</option>
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="edit_kilometrage">Kilométrage</label>
                    <input type="number" id="edit_kilometrage" name="kilometrage" required>
                </div>
                
                <div class="form-group">
                    <label for="edit_prix_location">Prix de location (par jour)</label>
                    <input type="number" id="edit_prix_location" name="prix_location" step="0.01" required>
                </div>
                
                <button type="submit" class="submit-btn">Enregistrer les modifications</button>
            </form>
        </div>
    </div>
    <!-- Popup notifications -->
    <div id="popup" class="popup"></div>
    
    <script src="admin.js"></script>
</body>
</html>
