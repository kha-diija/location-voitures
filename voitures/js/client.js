document.addEventListener("DOMContentLoaded", () => {
  loadVoitures();
  setActiveButton("voitures");

  document.querySelectorAll(".navbar button").forEach(btn => {
    btn.addEventListener("click", () => {
      const sectionId = getSectionIdFromText(btn.textContent);
      showSection(sectionId);
      setActiveButton(sectionId);
    });
  });
});

function getSectionIdFromText(text) {
  switch (text.trim()) {
    case "Voitures":
      return "voitures";
    case "Mes Réservations":
      return "reservations";
    case "Mon Compte":
      return "compte";
    default:
      return "voitures";
  }
}

function showSection(sectionId) {
  document.querySelectorAll(".section").forEach(sec => sec.classList.remove("active"));
  document.getElementById(sectionId).classList.add("active");

  if (sectionId === 'voitures') loadVoitures();
}

function setActiveButton(sectionId) {
  const buttons = document.querySelectorAll(".navbar button");
  buttons.forEach(btn => btn.classList.remove("active"));

  const mapping = {
    voitures: 0,
    reservations: 1,
    compte: 2
  };

  if (mapping[sectionId] !== undefined) {
    buttons[mapping[sectionId]].classList.add("active");
  }
}

function loadVoitures() {
  fetch('load_voitures.php')
    .then(res => res.text())
    .then(html => {
      document.getElementById('voitures').innerHTML = html;
    })
    .catch(error => console.error('Erreur lors du chargement des voitures:', error));
}

function showReservationForm(carId) {
  // Masquer tous les formulaires d'abord
  document.querySelectorAll('.reservation-form').forEach(form => {
    form.style.display = 'none';
  });

  const form = document.getElementById('reservation-form-' + carId);
  if (form) {
    form.style.display = 'block';
  }
}
document.addEventListener("DOMContentLoaded", function () {
  const allocateButtons = document.querySelectorAll(".allocate-btn");

  allocateButtons.forEach(button => {
    button.addEventListener("click", function () {
      const card = this.closest(".car-item");
      card.classList.add("show-form");
    });
  });
});


function showReservationForm(id) {
    document.getElementById('car-details-' + id).style.display = 'none';
    document.getElementById('reservation-form-' + id).style.display = 'block';
}

function hideReservationForm(id) {
    document.getElementById('reservation-form-' + id).style.display = 'none';
    document.getElementById('car-details-' + id).style.display = 'block';
}
document.querySelectorAll('.reservation-form form').forEach(form => {
  form.addEventListener('submit', function (e) {
      const dateDebut = new Date(this.date_debut.value);
      const dateFin = new Date(this.date_fin.value);
      if (dateDebut >= dateFin) {
          e.preventDefault();
          alert("La date de début doit être antérieure à la date de fin.");
      }
  });
});
