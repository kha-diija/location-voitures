<?php
// Connexion à la base de données
$host = 'localhost';
$user = 'root';
$password = '';
$database = 'LocationVoitures';

$loginError = '';

// Vérifier si une requête POST est reçue
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = new PDO("mysql:host=$host;dbname=$database;charset=utf8", $user, $password);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $stmt = $pdo->prepare("SELECT id_client, nom_client, prenom_client, motdepasse_client FROM Client WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && $user['motdepasse_client'] === $password) {
            session_start();
            $_SESSION['client_id'] = $user['id_client'];
            $_SESSION['client_name'] = $user['nom_client'] . ' ' . $user['prenom_client'];
            header("Location: client_dashboard.php");
            exit();
        } else {
            $loginError = 'Email ou mot de passe incorrect.';
        }
    } catch (Exception $e) {
        $loginError = 'Erreur serveur : ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CAR RENTAL - Location de Voitures</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>


<body>
<header>
    <div class="container">
        <div class="logo">
            <h1>CAR RENTAL</h1>
        </div>
        <nav class="nav-bar">
            <ul class="nav-links">
                <li><a href="#" class="active">Accueil</a></li>
                <li><a href="#about">À propos</a></li>
                <li><a href="#services">Nos Services </a></li>
                <li><a href="#fleet">Flotte</a></li>
                <li><a href="#contact">Contact</a></li>
            </ul>
            <a href="login.html" class="btn login-btn">Connexion</a>
        </nav>
        <div class="menu-toggle">
            <i class="fas fa-bars"></i>
        </div>
    </div>
</header>

<section class="hero">
  <div class="slider">
    <div class="slide" id="slide1">
      <div class="content">
        <h2>Louez une voiture en toute simplicité</h2>
        <p>Confort, sécurité et flexibilité pour tous vos trajets</p>
      </div>
    </div>
    <div class="slide" id="slide2">
      <div class="content">
        <h2>Une large gamme de véhicules</h2>
        <p>Choisissez parmi nos modèles récents adaptés à tous les besoins</p>
        <a href="#services" class="btn">Voir nos offres</a>
      </div>
    </div>
    <div class="slide" id="slide3">
      <div class="content">
        <h2>Assistance 24h/24</h2>
        <p>Roulez l'esprit tranquille grâce à notre service client dédié</p>
        <a href="#about" class="btn">En savoir plus</a>
      </div>
    </div>
    
    <!-- Ajout de contrôles de navigation -->
    <div class="slider-controls">
      <button id="prev" aria-label="Précédent"><i class="fas fa-chevron-left"></i></button>
      <div class="slider-indicators">
        <span class="indicator active" data-slide="0"></span>
        <span class="indicator" data-slide="1"></span>
        <span class="indicator" data-slide="2"></span>
      </div>
      <button id="next" aria-label="Suivant"><i class="fas fa-chevron-right"></i></button>
    </div>
  </div>
</section>

<section id="about" class="about">
    <div class="container">
        <div class="section-header">
            <h2>À propos de nous</h2>
            <p>Votre agence de location de confiance</p>
        </div>
        <div class="about-content">
            <div class="about-image">
                <img src="rental.png" alt="Agence car rental">
            </div>
            <div class="about-text">
                <h3>Qui sommes-nous ?</h3>
                <p>CAR RENTAL est une agence de location de voitures créée pour répondre aux besoins des particuliers et des professionnels en matière de mobilité. Nous proposons un large choix de véhicules à des prix compétitifs, avec un service client de qualité.</p>
                <h3>Notre mission</h3>
                <p>Offrir une expérience de location fluide, rapide et fiable, tout en garantissant des véhicules récents, bien entretenus et prêts à l’emploi.</p>
                <div class="stats">
                    <div class="stat-item">
                        <span class="stat-number">1000+</span>
                        <span class="stat-text">Locations effectuées</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">150+</span>
                        <span class="stat-text">Véhicules disponibles</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-number">10+</span>
                        <span class="stat-text">Années d’expérience</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="services" class="services">
    <div class="container">
        <div class="section-header">
            <h2>Nos Services</h2>
            <p>Des solutions adaptées à tous vos déplacements</p>
        </div>
        <div class="services-grid">
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-car"></i>
                </div>
                <h3>Location courte durée</h3>
                <p>Idéal pour vos déplacements ponctuels ou week-ends.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-road"></i>
                </div>
                <h3>Location longue durée</h3>
                <p>Des offres avantageuses pour vos besoins réguliers.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-briefcase"></i>
                </div>
                <h3>Location pour entreprises</h3>
                <p>Des solutions professionnelles pour vos équipes et collaborateurs.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-map-marker-alt"></i>
                </div>
                <h3>Livraison sur site</h3>
                <p>Nous livrons votre véhicule à l’adresse de votre choix.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-user-shield"></i>
                </div>
                <h3>Assurance complète</h3>
                <p>Roulez l’esprit tranquille avec une couverture complète.</p>
            </div>
            <div class="service-card">
                <div class="service-icon">
                    <i class="fas fa-cogs"></i>
                </div>
                <h3>Entretien régulier</h3>
                <p>Nos véhicules sont entretenus régulièrement par des professionnels.</p>
            </div>
        </div>
    </div>
</section>

<section id="fleet" class="events">
    <div class="container">
        <div class="section-header">
            <h2>Notre Flotte</h2>
            <p>Des véhicules pour tous les goûts et tous les budgets</p>
        </div>
        <div class="events-grid">
            <div class="event-card">
                <div class="event-image">
                    <img src="citadine.png" alt="Citadine Économique">
                </div>
                <div class="event-info">
                    <h3>Citadine Économique</h3>
                    <p class="event-date"><i class="fas fa-calendar-alt"></i> Dès 250DH/jour</p>
                    <p class="event-location"><i class="fas fa-gas-pump"></i> Faible consommation</p>
                    <p class="event-description">Parfaite pour les trajets urbains, pratique et abordable.</p>
                </div>
            </div>
            <div class="event-card">
                <div class="event-image">
                    <img src="suv.png" alt="SUV Confort">
                </div>
                <div class="event-info">
                    <h3>SUV Confort</h3>
                    <p class="event-date"><i class="fas fa-calendar-alt"></i> Dès 450DH/jour</p>
                    <p class="event-location"><i class="fas fa-snowflake"></i> Climatisation automatique</p>
                    <p class="event-description">Espace et puissance pour les longs trajets et les familles.</p>
                </div>
            </div>
            <div class="event-card">
                <div class="event-image">
                    <img src="car1.png" alt="Voiture de luxe">
                </div>
                <div class="event-info">
                    <h3>Voiture de Luxe</h3>
                    <p class="event-date"><i class="fas fa-calendar-alt"></i> Dès 200DH/jour</p>
                    <p class="event-location"><i class="fas fa-star"></i> Confort premium</p>
                    <p class="event-description">Idéale pour les occasions spéciales et les voyages d’affaires.</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="contact" class="contact">
    <div class="container">
        <div class="section-header">
            <h2>Contact</h2>
            <p>Restons en contact avec CAR RENTAL</p>
        </div>
        <div class="contact-info">
            <p><i class="fas fa-envelope"></i> Email : <a href="mailto:contact@carrental.com">contact@carrental.com</a></p>
            <p><i class="fas fa-phone-alt"></i> Téléphone : +212 6 12 34 56 78</p>
            <p><i class="fas fa-map-marker-alt"></i> Adresse : 123 Rue des Agences, Casablanca, Maroc</p>
            <p><i class="fas fa-clock"></i> Horaires : Lun - Ven, 9h - 18h</p>
        </div>
    </div>
</section>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    // Sélectionner tous les éléments nécessaires
    const slides = document.querySelectorAll('.slide');
    const prevBtn = document.getElementById('prev');
    const nextBtn = document.getElementById('next');
    const indicators = document.querySelectorAll('.indicator');
    
    // Variables pour le slider
    let currentSlide = 0;
    let slideInterval;
    
    // Fonction pour initialiser le slider
    function initSlider() {
      // Afficher le premier slide
      showSlide(0);
      // Démarrer le défilement automatique
      startSlideInterval();
      
      // Ajouter les écouteurs d'événements pour les boutons
      if (prevBtn) prevBtn.addEventListener('click', prevSlide);
      if (nextBtn) nextBtn.addEventListener('click', nextSlide);
      
      // Ajouter les écouteurs d'événements pour les indicateurs
      indicators.forEach((indicator, index) => {
        indicator.addEventListener('click', () => {
          showSlide(index);
          resetInterval();
        });
      });
      
      // Arrêter le défilement automatique lorsque la souris est sur le slider
      document.querySelector('.slider').addEventListener('mouseenter', () => {
        clearInterval(slideInterval);
      });
      
      // Reprendre le défilement automatique lorsque la souris quitte le slider
      document.querySelector('.slider').addEventListener('mouseleave', () => {
        startSlideInterval();
      });
    }
    
    // Fonction pour afficher un slide spécifique
    function showSlide(index) {
      // Masquer tous les slides
      slides.forEach(slide => {
        slide.classList.remove('active');
      });
      
      // Désactiver tous les indicateurs
      indicators.forEach(indicator => {
        indicator.classList.remove('active');
      });
      
      // Mettre à jour l'index du slide courant
      currentSlide = index;
      
      // Si l'index est hors limites, revenir au début
      if (currentSlide < 0) {
        currentSlide = slides.length - 1;
      } else if (currentSlide >= slides.length) {
        currentSlide = 0;
      }
      
      // Afficher le slide courant
      slides[currentSlide].classList.add('active');
      
      // Activer l'indicateur correspondant
      indicators[currentSlide].classList.add('active');
    }
    
  




    
    
    // Fonction pour démarrer le défilement automatique
    function startSlideInterval() {
      slideInterval = setInterval(nextSlide, 5000); // Change de slide toutes les 5 secondes
    }
    
    // Fonction pour réinitialiser l'intervalle
    function resetInterval() {
      clearInterval(slideInterval);
      startSlideInterval();
    }
    
    // Initialiser le slider
    initSlider();
    
    // Afficher un message dans la console pour vérifier que le script s'exécute
    console.log('Slider initialized');
  });
</script>

</body>
</html>
