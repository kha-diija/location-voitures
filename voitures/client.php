<?php session_start(); 
// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['user_id']) || !isset($_SESSION['role']) || $_SESSION['role'] !== 'client') {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de bord Client</title>
  <style>
    /* Styles globaux */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
      font-family: 'Arial', sans-serif;
    }
    
    body {
      background-color: #f0f8ff;
      color: #333;
      line-height: 1.6;
    }
    
    /* Style pour le menu en haut */
    .navbar {
      display: flex;
      background-color: #e6f9ff; /* Bleu ciel très clair */
      padding: 15px 0;
      justify-content: center;
      gap: 30px;
      box-shadow: 0 2px 10px rgba(0, 150, 199, 0.15);
      position: sticky;
      top: 0;
      z-index: 100;
    }
    
    .navbar button {
      background-color: transparent;
      color: #0096c7;
      border: 2px solid #0096c7;
      padding: 10px 20px;
      border-radius: 8px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .navbar button:hover,
    .navbar button.active {
      background-color: #0096c7;
      color: white;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 150, 199, 0.2);
    }
    
    /* Style pour les sections */
    .section {
      display: none;
      padding: 30px;
      max-width: 1200px;
      margin: 0 auto;
    }
    
    .section.active {
      display: block;
    }
    
    .section h2 {
      color: #0096c7;
      margin-bottom: 20px;
      text-align: center;
      font-size: 28px;
    }
    
    /* Style pour la galerie de voitures */
    .gallery {
      display: flex;
      flex-wrap: wrap;
      gap: 20px;
      justify-content: flex-start;
    }
    
    .car-item {
      width: calc(33.33% - 20px); /* 3 voitures par ligne */
      box-sizing: border-box;
      text-align: center;
      border: 1px solid #ccc;
      padding: 10px;
      border-radius: 8px;
      box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
      position: relative;
      margin-bottom: 20px;
      transition: transform 0.3s ease;
      cursor: pointer;
      background-color: white;
    }
    
    .car-item:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 12px rgba(0, 150, 199, 0.2);
    }
    
    .car-image {
      width: 100%;
      height: 200px;
      object-fit: cover;
      border-radius: 6px;
      transition: transform 0.3s ease;
    }
    
    /* Style pour les réservations */
    .reservation-card {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      margin-bottom: 20px;
      overflow: hidden;
      display: flex;
      flex-direction: column;
    }
    
    .reservation-header {
      background-color: #e6f9ff;
      padding: 15px;
      display: flex;
      justify-content: space-between;
      align-items: center;
    }
    
    .reservation-header h3 {
      color: #0096c7;
      margin: 0;
    }
    
    .reservation-status {
      padding: 5px 10px;
      border-radius: 20px;
      font-size: 14px;
      font-weight: bold;
    }
    
    .status-confirmed {
      background-color: #d4edda;
      color: #155724;
    }
    
    .status-pending {
      background-color: #fff3cd;
      color: #856404;
    }
    
    .status-cancelled {
      background-color: #f8d7da;
      color: #721c24;
    }
    
    .reservation-body {
      padding: 15px;
      display: flex;
    }
    
    .reservation-image {
      width: 150px;
      height: 100px;
      object-fit: cover;
      border-radius: 5px;
      margin-right: 15px;
    }
    
    .reservation-details {
      flex: 1;
    }
    
    .reservation-details p {
      margin: 5px 0;
    }
    
    .reservation-actions {
      padding: 15px;
      background-color: #f9f9f9;
      text-align: right;
    }
    
    .btn-cancel {
      background-color: #f8d7da;
      color: #721c24;
      border: none;
      padding: 8px 15px;
      border-radius: 5px;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-cancel:hover {
      background-color: #e74c3c;
      color: white;
    }
    
    /* Style pour le profil */
    .profile-container {
      background-color: white;
      border-radius: 10px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      padding: 30px;
      max-width: 800px;
      margin: 0 auto;
    }
    
    .profile-header {
      text-align: center;
      margin-bottom: 30px;
    }
    
    .profile-header h3 {
      color: #0096c7;
      font-size: 24px;
      margin-bottom: 10px;
    }
    
    .profile-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 20px;
    }
    
    .form-group {
      margin-bottom: 15px;
    }
    
    .form-group label {
      display: block;
      margin-bottom: 5px;
      font-weight: bold;
      color: #0096c7;
    }
    
    .form-group input {
      width: 100%;
      padding: 10px;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      transition: border-color 0.3s;
    }
    
    .form-group input:focus {
      border-color: #44f6ff;
      outline: none;
      box-shadow: 0 0 5px rgba(68, 246, 255, 0.5);
    }
    
    .form-actions {
      grid-column: span 2;
      text-align: center;
      margin-top: 20px;
    }
    
    .btn-update {
      background-color: #44f6ff;
      color: white;
      border: none;
      padding: 12px 25px;
      border-radius: 5px;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: all 0.3s ease;
    }
    
    .btn-update:hover {
      background-color: #65edff;
      transform: translateY(-2px);
      box-shadow: 0 4px 8px rgba(0, 150, 199, 0.3);
    }
    
    /* Style pour le modal */
    .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.7);
        z-index: 1000;
        backdrop-filter: blur(5px);
    }

    .modal {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 60%;
        height: 80%;
        background-color: rgba(222, 252, 254, 0.95); /* Bleu ciel semi-transparent */
        border-radius: 15px;
        box-shadow: 0 5px 25px rgba(0, 150, 199, 0.4);
        z-index: 1001;
        overflow: auto;
        backdrop-filter: blur(10px);
    }

    .modal-content {
        position: relative;
        width: 100%;
        height: 100%;
        padding: 20px;
        box-sizing: border-box;
    }

    .close-modal {
        position: absolute;
        top: 10px;
        right: 15px;
        font-size: 28px;
        font-weight: bold;
        color: #0096c7;
        cursor: pointer;
        z-index: 1002;
        background-color: white;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .close-modal:hover {
        color: #333;
        background-color: #f0f0f0;
    }

    .modal-body {
        display: flex;
        flex-direction: column;
        height: 100%;
    }

    .modal-image-container {
        flex: 2;
        padding: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
    }

    .modal-image {
        max-width: 100%;
        max-height: 250px;
        object-fit: contain;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        transition: transform 0.3s ease;
    }

    .modal-image:hover {
        transform: scale(1.02);
    }

    .modal-details {
        flex: 1;
        padding: 15px;
        background-color: rgba(255, 255, 255, 0.8);
        border-radius: 10px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
    }

    .modal-details h3 {
        margin-top: 0;
        color: #0096c7;
        font-size: 22px;
        text-align: center;
        border-bottom: 2px solid #44f6ff;
        padding-bottom: 8px;
        margin-bottom: 15px;
    }

    .modal-details p {
        margin: 8px 0;
        font-size: 15px;
        color: #333;
    }

    /* Style pour le bouton Allouer */
    .allocate-btn {
        background-color: #44f6ff;
        color: white;
        padding: 10px 20px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        margin: 15px auto 0;
        font-size: 16px;
        font-weight: bold;
        transition: all 0.3s ease;
        display: block;
        width: 80%;
        text-align: center;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .allocate-btn:hover {
        background-color: #65edff;
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.3);
    }

    /* Style pour le formulaire de réservation */
    .reservation-form {
        display: none;
        margin-top: 15px;
        padding: 15px;
        background-color: #f9f9f9;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
    }

    .form-group {
        margin-bottom: 12px;
    }

    .form-group label {
        display: block;
        margin-bottom: 5px;
        font-weight: bold;
        color: #0096c7;
    }

    .form-group input[type="date"] {
        width: 100%;
        padding: 8px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
        transition: border-color 0.3s;
    }

    .form-group input[type="date"]:focus {
        border-color: #44f6ff;
        outline: none;
        box-shadow: 0 0 5px rgba(68, 246, 255, 0.5);
    }

    .form-buttons {
        display: flex;
        justify-content: space-between;
        margin-top: 15px;
    }

    .confirm-btn, .cancel-btn {
        padding: 8px 15px;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-weight: bold;
        transition: all 0.3s ease;
        flex: 1;
        margin: 0 5px;
    }

    .confirm-btn {
        background-color: #44f6ff;
        color: white;
    }

    .confirm-btn:hover {
        background-color: #65edff;
        transform: translateY(-2px);
    }

    .cancel-btn {
        background-color: #f1f1f1;
        color: #333;
    }

    .cancel-btn:hover {
        background-color: #ddd;
        transform: translateY(-2px);
    }

    /* Style pour les alertes personnalisées */
    .custom-alert {
        display: none;
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        width: 300px;
        background-color: rgba(173, 239, 255, 0.95);
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
        transition: background-color 0.3s ease;
    }

    .alert-content button:hover {
        background-color: #65edff;
    }

    /* Media Queries pour la réactivité */
    @media (max-width: 992px) {
        .car-item {
            width: calc(50% - 20px); /* 2 voitures par ligne sur écran moyen */
        }
        
        .profile-form {
            grid-template-columns: 1fr;
        }
        
        .form-actions {
            grid-column: span 1;
        }
        
        .modal {
            width: 80%;
            height: 70%;
        }
    }

    @media (max-width: 768px) {
        .navbar {
            flex-direction: column;
            gap: 10px;
            padding: 10px;
        }
        
        .navbar button {
            width: 100%;
        }
        
        .modal-body {
            flex-direction: column;
        }
        
        .modal-image-container {
            margin-bottom: 15px;
        }
        
        .reservation-body {
            flex-direction: column;
        }
        
        .reservation-image {
            width: 100%;
            margin-right: 0;
            margin-bottom: 15px;
        }
    }

    @media (max-width: 576px) {
        .car-item {
            width: 100%; /* 1 voiture par ligne sur petit écran */
        }
        
        .section {
            padding: 15px;
        }
        
        .modal {
            width: 95%;
            height: 80%;
        }
    }
    
    @media (min-width: 768px) {
        .modal-body {
            flex-direction: row;
        }
        
        .modal-image-container {
            flex: 3;
            margin-bottom: 0;
            margin-right: 15px;
        }
        /* Style pour le bouton de déconnexion */
.btn-deconnexion {
  background-color: #e74c3c !important;
  color: white !important;
  border: 2px solid #c0392b !important;
}

.btn-deconnexion:hover {
  background-color: #c0392b !important;
  color: white !important;
  transform: translateY(-2px);
  box-shadow: 0 4px 8px rgba(192, 57, 43, 0.3) !important;
}
        
        .modal-details {
            flex: 2;
        }
    }
  </style>
</head>
<body>
  <div class="navbar">
    <button id="btn-voitures" onclick="showSection('voitures')" class="active">Voitures</button>
    <button id="btn-reservations" onclick="showSection('reservations')">Mes Réservations</button>
    <button id="btn-compte" onclick="showSection('compte')">Mon Compte</button>
    <button id="btn-deconnexion" onclick="deconnexion()" class="btn-deconnexion">Déconnexion</button>
  </div>

  <div id="contenu">
    <div id="voitures" class="section active">
      <h2>Voitures Disponibles</h2>
      <div id="voitures-content"></div>
    </div>
    
    <div id="reservations" class="section">
      <h2>Mes Réservations</h2>
      <div id="reservations-content"></div>
    </div>
    
    <div id="compte" class="section">
      <h2>Mon Profil</h2>
      <div id="compte-content"></div>
    </div>
  </div>

  <!-- Overlay pour le fond sombre -->
  <div id="overlay" class="overlay"></div>

  <!-- Container pour les popups d'alerte -->
  <div id="custom-alert" class="custom-alert">
    <div class="alert-content">
        <p id="alert-message"></p>
        <button onclick="closeAlert()">OK</button>
    </div>
  </div>

  <!-- Conteneur pour les modals de voitures -->
  <div id="car-modals-container"></div>

  <script>
    // Fonction pour afficher une section
    function showSection(sectionId) {
      // Cacher toutes les sections
      document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
      });
      
      // Désactiver tous les boutons
      document.querySelectorAll('.navbar button').forEach(button => {
        button.classList.remove('active');
      });
      
      // Afficher la section demandée
      document.getElementById(sectionId).classList.add('active');
      
      // Activer le bouton correspondant
      document.getElementById('btn-' + sectionId).classList.add('active');
      
      // Charger le contenu approprié
      if (sectionId === 'voitures') {
        loadVoitures();
      } else if (sectionId === 'reservations') {
        loadReservations();
      } else if (sectionId === 'compte') {
        loadCompte();
      }
    }
    
    // Fonction pour charger les voitures
    function loadVoitures() {
      fetch('load_voitures.php')
        .then(response => response.text())
        .then(data => {
          document.getElementById('voitures-content').innerHTML = data;
        })
        .catch(error => {
          console.error('Erreur lors du chargement des voitures:', error);
        });
    }
    
    // Fonction pour charger les réservations
    function loadReservations() {
      fetch('load_reservations.php')
        .then(response => response.text())
        .then(data => {
          document.getElementById('reservations-content').innerHTML = data;
        })
        .catch(error => {
          console.error('Erreur lors du chargement des réservations:', error);
        });
    }
    
    // Fonction pour charger les informations du compte
    function loadCompte() {
      fetch('load_compte.php')
        .then(response => response.text())
        .then(data => {
          document.getElementById('compte-content').innerHTML = data;
        })
        .catch(error => {
          console.error('Erreur lors du chargement du compte:', error);
        });
    }
    // Fonction pour la déconnexion
function deconnexion() {
  if (confirm('Êtes-vous sûr de vouloir vous déconnecter ?')) {
    window.location.href = 'logout.php';
  }
}
    
    // Fonction pour ouvrir le modal
    function openModal(id, img, marque, modele, carburant, kilometrage, prix) {
      // Vérifier si l'image existe, sinon utiliser une image par défaut
      const imgSrc = img || 'images/default_car_image.png';
      
      // Créer le modal s'il n'existe pas déjà
      let modal = document.getElementById('modal-' + id);
      if (!modal) {
        const modalHTML = `
          <div id="modal-${id}" class="modal">
            <div class="modal-content">
              <span class="close-modal" onclick="closeModal('${id}')">&times;</span>
              <div class="modal-body">
                <div class="modal-image-container">
                  <img src="${imgSrc}" alt="Voiture ${id}" class="modal-image" onerror="this.src='images/default_car_image.png'">
                </div>
                <div class="modal-details">
                  <h3>${marque} ${modele}</h3>
                  <p><strong>Immatriculation:</strong> ${id}</p>
                  <p><strong>Carburant:</strong> ${carburant}</p>
                  <p><strong>Kilométrage:</strong> ${kilometrage} km</p>
                  <p><strong>Prix:</strong> ${prix} DH/jour</p>
                  <button class="allocate-btn" onclick="showReservationForm('${id}')">Allouer</button>
                  
                  <div id="reservation-form-${id}" class="reservation-form">
                    <form method="POST" action="reservation.php" onsubmit="return validateDates(this)">
                      <input type="hidden" name="num_immatriculation" value="${id}">
                      <div class="form-group">
                        <label>Date début:</label>
                        <input type="date" name="date_debut" min="${new Date().toISOString().split('T')[0]}" required>
                      </div>
                      <div class="form-group">
                        <label>Date fin:</label>
                        <input type="date" name="date_fin" min="${new Date().toISOString().split('T')[0]}" required>
                      </div>
                      <div class="form-buttons">
                        <button type="submit" class="confirm-btn">Confirmer</button>
                        <button type="button" class="cancel-btn" onclick="hideReservationForm('${id}')">Annuler</button>
                      </div>
                    </form>
                  </div>
                </div>
              </div>
            </div>
          </div>
        `;
        
        document.getElementById('car-modals-container').innerHTML += modalHTML;
        modal = document.getElementById('modal-' + id);
      }
      
      document.getElementById('overlay').style.display = 'block';
      modal.style.display = 'block';
      document.body.style.overflow = 'hidden'; // Empêcher le défilement
    }

    // Fonction pour fermer le modal
    function closeModal(id) {
      document.getElementById('overlay').style.display = 'none';
      document.getElementById('modal-' + id).style.display = 'none';
      document.body.style.overflow = 'auto'; // Réactiver le défilement
      
      // Cacher le formulaire de réservation si ouvert
      hideReservationForm(id);
    }

    // Fonction pour afficher le formulaire de réservation
    function showReservationForm(id) {
      document.getElementById('reservation-form-' + id).style.display = 'block';
    }

    // Fonction pour cacher le formulaire de réservation
    function hideReservationForm(id) {
      document.getElementById('reservation-form-' + id).style.display = 'none';
    }

    // Fonction pour valider les dates
    function validateDates(form) {
      const dateDebut = new Date(form.date_debut.value);
      const dateFin = new Date(form.date_fin.value);
      
      if (dateDebut >= dateFin) {
        showCustomAlert("La date de début doit être antérieure à la date de fin.");
        return false;
      }
      return true;
    }

    // Fonction pour afficher une alerte personnalisée
    function showCustomAlert(message) {
      document.getElementById('alert-message').textContent = message;
      document.getElementById('custom-alert').style.display = 'block';
      document.getElementById('overlay').style.display = 'block';
    }

    // Fonction pour fermer l'alerte personnalisée
    function closeAlert() {
      document.getElementById('custom-alert').style.display = 'none';
      document.getElementById('overlay').style.display = 'none';
    }

    // Fonction pour annuler une réservation
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
            showCustomAlert('Réservation annulée avec succès.');
            loadReservations(); // Recharger les réservations
          } else {
            showCustomAlert('Erreur lors de l\'annulation: ' + data.message);
          }
        })
        .catch(error => {
          console.error('Erreur:', error);
          showCustomAlert('Une erreur est survenue. Veuillez réessayer.');
        });
      }
    }

    // Fonction pour mettre à jour le profil
    function updateProfile(event) {
      event.preventDefault();
      
      const form = document.getElementById('profile-form');
      const formData = new FormData(form);
      
      fetch('update_profile.php', {
        method: 'POST',
        body: formData
      })
      .then(response => response.json())
      .then(data => {
        if (data.success) {
          showCustomAlert('Profil mis à jour avec succès.');
          loadCompte(); // Recharger les informations du compte
        } else {
          showCustomAlert('Erreur lors de la mise à jour: ' + data.message);
        }
      })
      .catch(error => {
        console.error('Erreur:', error);
        showCustomAlert('Une erreur est survenue. Veuillez réessayer.');
      });
    }

    // Fermer le modal si on clique sur l'overlay
    document.getElementById('overlay').addEventListener('click', function() {
      const modals = document.querySelectorAll('.modal');
      modals.forEach(modal => {
        if (modal.style.display === 'block') {
          const id = modal.id.replace('modal-', '');
          closeModal(id);
        }
      });
    });

    // Charger les voitures au chargement de la page
    document.addEventListener('DOMContentLoaded', function() {
      loadVoitures();
    });
  </script>
</body>
</html>