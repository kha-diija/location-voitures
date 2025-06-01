// Fonction pour afficher une popup personnalisée
function showPopup(message) {
  document.getElementById("popup-text").innerText = message
  document.getElementById("popup-message").classList.remove("hidden")
  document.getElementById("overlay").style.display = "block"
}

// Fonction pour fermer la popup
function closePopup() {
  document.getElementById("popup-message").classList.add("hidden")
  document.getElementById("overlay").style.display = "none"
  location.reload() // recharge la liste
}

// Fonction pour annuler une réservation
function cancelReservation(reservationId) {
  if (confirm("Êtes-vous sûr de vouloir annuler cette réservation ?")) {
    // Afficher un message de chargement
    showPopup("Traitement en cours...")

    // Utiliser FormData pour envoyer les données
    const formData = new FormData()
    formData.append("reservation_id", reservationId)

    fetch("cancel_reservation.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        // Vérifier si la réponse est OK
        if (!response.ok) {
          return response.text().then((text) => {
            throw new Error("Erreur serveur: " + text)
          })
        }
        return response.json()
      })
      .then((data) => {
        if (data.success) {
          let message = "Réservation annulée avec succès."
          if (data.remboursement) {
            message += " Montant remboursé: " + data.remboursement + "DH"
          }
          showPopup(message)

          // Attendre un peu avant de recharger
          setTimeout(() => {
            loadReservations()
          }, 2000)
        } else {
          showPopup("Erreur: " + data.message)
        }
      })
      .catch((error) => {
        console.error("Erreur:", error)
        showPopup("Une erreur est survenue. Veuillez réessayer.")
      })
  }
}

// Fonction pour charger les réservations
function loadReservations() {
  fetch("load_reservations.php")
    .then((response) => response.text())
    .then((data) => {
      document.getElementById("reservations-content").innerHTML = data
    })
    .catch((error) => {
      console.error("Erreur lors du chargement des réservations:", error)
      showPopup("Erreur lors du chargement des réservations.")
    })}