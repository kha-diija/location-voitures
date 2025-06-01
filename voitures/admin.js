document.addEventListener("DOMContentLoaded", () => {
  // Fonction pour afficher des notifications popup
  function showPopup(message, type = "success") {
    const popup = document.getElementById("popup")
    popup.textContent = message
    popup.className = `popup ${type}`
    popup.style.display = "block"

    // Masquer le popup après 3 secondes
    setTimeout(() => {
      popup.style.display = "none"
    }, 3500)
  }
  // Gestion des boutons de navigation
  const voitureBtn = document.getElementById("voitureBtn")
  const voitureSubMenu = document.getElementById("voitureSubMenu")
  const ajouterVoitureBtn = document.getElementById("ajouterVoitureBtn")
  const afficherVoitureBtn = document.getElementById("afficherVoitureBtn")
  const reservationBtn = document.getElementById("reservationBtn")
  const utilisateurBtn = document.getElementById("utilisateurBtn")
  const amendeBtn = document.getElementById("amendeBtn")

  // Sections de contenu
  const defaultContent = document.getElementById("defaultContent")
  const ajouterVoitureContent = document.getElementById("ajouterVoitureContent")
  const afficherVoitureContent = document.getElementById("afficherVoitureContent")
  const reservationContent = document.getElementById("reservationContent")
  const utilisateurContent = document.getElementById("utilisateurContent")
  const amendeContent = document.getElementById("amendeContent")

  // Fonction pour masquer toutes les sections
  function hideAllSections() {
    defaultContent.style.display = "none"
    ajouterVoitureContent.style.display = "none"
    afficherVoitureContent.style.display = "none"
    reservationContent.style.display = "none"
    utilisateurContent.style.display = "none"
    amendeContent.style.display = "none"

    // Réinitialiser les classes actives
    document.querySelectorAll(".nav-button").forEach((btn) => {
      btn.classList.remove("active")
    })

    document.querySelectorAll(".sub-button").forEach((btn) => {
      btn.classList.remove("active")
    })

    // Masquer tous les sous-menus
    document.querySelectorAll(".sub-menu").forEach((menu) => {
      menu.classList.remove("active")
    })
  }

  // Gestion du menu Voiture
  voitureBtn.addEventListener("click", () => {
    voitureSubMenu.classList.toggle("active")
    voitureBtn.classList.toggle("active")
  })

  // Gestion du sous-menu Ajouter Voiture
  ajouterVoitureBtn.addEventListener("click", () => {
    hideAllSections()
    ajouterVoitureContent.style.display = "block"
    voitureBtn.classList.add("active")
    ajouterVoitureBtn.classList.add("active")
    voitureSubMenu.classList.add("active")
  })

  // Gestion du sous-menu Afficher Voiture
  afficherVoitureBtn.addEventListener("click", () => {
    hideAllSections()
    afficherVoitureContent.style.display = "block"
    voitureBtn.classList.add("active")
    afficherVoitureBtn.classList.add("active")
    voitureSubMenu.classList.add("active")

    // Charger les données des voitures
    loadVoitureData()
  })

  // Gestion du menu Réservation
  reservationBtn.addEventListener("click", () => {
    hideAllSections()
    reservationContent.style.display = "block"
    reservationBtn.classList.add("active")

    // Charger les données des réservations
    loadReservationData()
  })

  // Gestion du menu Utilisateur
  utilisateurBtn.addEventListener("click", () => {
    hideAllSections()
    utilisateurContent.style.display = "block"
    utilisateurBtn.classList.add("active")

    // Charger les données des clients
    loadClientData()
  })

  // Gestion du menu Amende
  amendeBtn.addEventListener("click", () => {
    hideAllSections()
    amendeContent.style.display = "block"
    amendeBtn.classList.add("active")

    // Charger les données des amendes
    loadAmendeData()
  })

  // Fonction pour charger les données des voitures
  function loadVoitureData(searchField = null, searchValue = null) {
    const tableContainer = document.getElementById("voitureTableContainer")
    tableContainer.innerHTML = '<div class="loading">Chargement des données...</div>'

    // Construire l'URL pour la requête AJAX
    let url = "get_voitures.php"
    if (searchField && searchValue) {
      url += `?field=${encodeURIComponent(searchField)}&value=${encodeURIComponent(searchValue)}`
    }

    // Effectuer la requête AJAX
    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.text()
      })
      .then((data) => {
        tableContainer.innerHTML = data

        // Ajouter des écouteurs d'événements pour les boutons d'action
        setupVoitureActionButtons()
      })
      .catch((error) => {
        console.error("Erreur lors du chargement des données:", error)
        tableContainer.innerHTML =
          '<p class="error-message">Erreur lors du chargement des données: ' + error.message + "</p>"
        showPopup("Erreur lors du chargement des données", "error")
      })
  }

  // Fonction pour charger les données des réservations
  function loadReservationData() {
    const tableContainer = document.getElementById("reservationTableContainer")
    tableContainer.innerHTML = '<div class="loading">Chargement des données...</div>'

    fetch("get_reservations.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.text()
      })
      .then((data) => {
        tableContainer.innerHTML = data
      })
      .catch((error) => {
        console.error("Erreur lors du chargement des réservations:", error)
        tableContainer.innerHTML =
          '<p class="error-message">Erreur lors du chargement des données: ' + error.message + "</p>"
        showPopup("Erreur lors du chargement des réservations", "error")
      })
  }

  // Fonction pour charger les données des clients
  function loadClientData() {
    const tableContainer = document.getElementById("clientTableContainer")
    tableContainer.innerHTML = '<div class="loading">Chargement des données...</div>'

    fetch("get_clients.php")
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.text()
      })
      .then((data) => {
        tableContainer.innerHTML = data
      })
      .catch((error) => {
        console.error("Erreur lors du chargement des clients:", error)
        tableContainer.innerHTML =
          '<p class="error-message">Erreur lors du chargement des données: ' + error.message + "</p>"
        showPopup("Erreur lors du chargement des clients", "error")
      })
  }

  // Fonction pour charger les données des amendes
  function loadAmendeData(searchValue = null) {
    const tableContainer = document.getElementById("amendeTableContainer")
    tableContainer.innerHTML = '<div class="loading">Chargement des données...</div>'

    // Construire l'URL pour la requête AJAX
    let url = "get_amendes.php"
    if (searchValue) {
      url += `?search=${encodeURIComponent(searchValue)}`
    }

    fetch(url)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.text()
      })
      .then((data) => {
        tableContainer.innerHTML = data

        // Ajouter des écouteurs d'événements pour les boutons de suppression
        setupAmendeDeleteButtons()
      })
      .catch((error) => {
        console.error("Erreur lors du chargement des amendes:", error)
        tableContainer.innerHTML =
          '<p class="error-message">Erreur lors du chargement des données: ' + error.message + "</p>"
        showPopup("Erreur lors du chargement des amendes", "error")
      })
  }

  // Configuration des boutons d'action pour les voitures
  function setupVoitureActionButtons() {
    // Boutons de modification
    document.querySelectorAll(".edit-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const voitureId = this.getAttribute("data-id")
        openEditVoitureModal(voitureId)
      })
    })

    // Boutons de suppression
    document.querySelectorAll(".delete-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const voitureId = this.getAttribute("data-id")
        if (confirm("Êtes-vous sûr de vouloir supprimer cette voiture ?")) {
          deleteVoiture(voitureId)
        }
      })
    })
  }

  // Configuration des boutons de suppression pour les amendes
  function setupAmendeDeleteButtons() {
    document.querySelectorAll(".delete-amende-btn").forEach((button) => {
      button.addEventListener("click", function () {
        const amendeId = this.getAttribute("data-id")
        if (confirm("Êtes-vous sûr de vouloir supprimer cette amende ?")) {
          deleteAmende(amendeId)
        }
      })
    })
  }

  // Fonction pour ouvrir le modal de modification de voiture
  function openEditVoitureModal(voitureId) {
    // Récupérer les données de la voiture
    fetch(`get_voiture.php?id=${voitureId}`)
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        // Remplir le formulaire avec les données
        document.getElementById("edit_num_immatriculation").value = data.num_immatriculation
        document.getElementById("edit_marque").value = data.marque
        document.getElementById("edit_modele").value = data.modele
        document.getElementById("edit_carburant").value = data.carburant
        document.getElementById("edit_statut_voit").value = data.statut_voit
        document.getElementById("edit_kilometrage").value = data.kilometrage
        document.getElementById("edit_prix_location").value = data.prix_location

        // Afficher le modal
        const modal = document.getElementById("modifierVoitureModal")
        modal.style.display = "block"
      })
      .catch((error) => {
        console.error("Erreur lors de la récupération des données:", error)
        showPopup("Erreur lors de la récupération des données de la voiture: " + error.message, "error")
      })
  }

  // Fonction pour supprimer une voiture
  function deleteVoiture(voitureId) {
    const formData = new FormData()
    formData.append("id", voitureId)

    fetch("supprimer_voiture.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        if (data.success) {
          showPopup("Voiture supprimée avec succès.")
          loadVoitureData() // Recharger les données
        } else {
          showPopup("Erreur: " + data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Erreur:", error)
        showPopup("Une erreur est survenue lors de la suppression: " + error.message, "error")
      })
  }

  // Fonction pour supprimer une amende
  function deleteAmende(amendeId) {
    const formData = new FormData()
    formData.append("id", amendeId)

    fetch("supprimer_amende.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        if (data.success) {
          showPopup("Amende supprimée avec succès.")
          loadAmendeData() // Recharger les données
        } else {
          showPopup("Erreur: " + data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Erreur:", error)
        showPopup("Une erreur est survenue lors de la suppression: " + error.message, "error")
      })
  }

  // Gestion du modal
  const modal = document.getElementById("modifierVoitureModal")
  const closeBtn = document.querySelector(".close")

  closeBtn.addEventListener("click", () => {
    modal.style.display = "none"
  })

  window.addEventListener("click", (event) => {
    if (event.target === modal) {
      modal.style.display = "none"
    }
  })

  // Gestion du formulaire de modification
  document.getElementById("modifierVoitureForm").addEventListener("submit", function (event) {
    event.preventDefault()

    const formData = new FormData(this)

    fetch("modifier_voiture.php", {
      method: "POST",
      body: formData,
    })
      .then((response) => {
        if (!response.ok) {
          throw new Error(`Erreur HTTP: ${response.status}`)
        }
        return response.json()
      })
      .then((data) => {
        if (data.success) {
          showPopup("Voiture modifiée avec succès.")
          modal.style.display = "none"
          loadVoitureData() // Recharger les données
        } else {
          showPopup("Erreur: " + data.message, "error")
        }
      })
      .catch((error) => {
        console.error("Erreur:", error)
        showPopup("Une erreur est survenue lors de la modification: " + error.message, "error")
      })
  })

  // Gestion de la recherche de voitures
  document.getElementById("searchButton").addEventListener("click", () => {
    const searchField = document.getElementById("searchField").value
    const searchValue = document.getElementById("searchInput").value.trim()

    if (searchValue !== "") {
      loadVoitureData(searchField, searchValue)
    } else {
      loadVoitureData()
    }
  })

  // Ajouter un événement pour la touche Entrée dans le champ de recherche
  document.getElementById("searchInput").addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      event.preventDefault()
      document.getElementById("searchButton").click()
    }
  })

  // Gestion de la recherche d'amendes
  document.getElementById("searchAmendeButton").addEventListener("click", () => {
    const searchValue = document.getElementById("searchAmendeInput").value.trim()

    if (searchValue !== "") {
      loadAmendeData(searchValue)
    } else {
      loadAmendeData()
    }
  })

  // Ajouter un événement pour la touche Entrée dans le champ de recherche d'amendes
  document.getElementById("searchAmendeInput").addEventListener("keypress", (event) => {
    if (event.key === "Enter") {
      event.preventDefault()
      document.getElementById("searchAmendeButton").click()
    }
  })
})
