document.addEventListener('DOMContentLoaded', function() {
  // Variables pour le slider
  const slides = document.querySelectorAll('.slide');
  const prevBtn = document.getElementById('prev');
  const nextBtn = document.getElementById('next');
  let currentSlide = 0;
  let autoSlideInterval;

  // Fonction pour afficher un slide
  function showSlide(n) {
      // Masquer tous les slides
      slides.forEach(slide => {
          slide.classList.remove('current');
          slide.classList.remove('next');
          slide.classList.remove('prev');
      });

      // Ajouter la classe 'current' au slide courant
      slides[n].classList.add('current');

      // Ajouter la classe 'next' et 'prev' pour les autres slides
      const nextSlide = (n + 1) % slides.length;
      const prevSlide = (n - 1 + slides.length) % slides.length;

      slides[nextSlide].classList.add('next');
      slides[prevSlide].classList.add('prev');
  }

  // Fonction pour passer au slide suivant
  function nextSlide() {
      currentSlide++;
      if (currentSlide >= slides.length) {
          currentSlide = 0;
      }
      showSlide(currentSlide);
  }

  // Fonction pour revenir au slide précédent
  function prevSlide() {
      currentSlide--;
      if (currentSlide < 0) {
          currentSlide = slides.length - 1;
      }
      showSlide(currentSlide);
  }

  // Démarrer le slider automatique
  function startAutoSlide() {
      autoSlideInterval = setInterval(nextSlide, 5000);
  }

  // Arrêter le slider automatique
  function stopAutoSlide() {
      clearInterval(autoSlideInterval);
  }

  // Événements pour les boutons du slider
  prevBtn.addEventListener('click', () => {
      prevSlide();
      stopAutoSlide();
      startAutoSlide();
  });

  nextBtn.addEventListener('click', () => {
      nextSlide();
      stopAutoSlide();
      startAutoSlide();
  });

  // Démarrer le slider automatique au chargement
  startAutoSlide();

  // Initialiser le slider
  showSlide(currentSlide);
});
