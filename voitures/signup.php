<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription Client</title>
  <link rel="stylesheet" href="styles.css">
  <script>
    function validerFormulaire() {
      const motDePasse = document.getElementById("mdp").value;
      const confirmation = document.getElementById("confirm_mdp").value;

      if (motDePasse !== confirmation) {
        alert("Les mots de passe ne correspondent pas.");
        return false;
      }
      return true;
    }

    window.onload = function () {
  const params = new URLSearchParams(window.location.search);
  const popupType = params.get('popup');
  const popup = document.getElementById("popup");
  const message = document.getElementById("popup-message");

  // Vérifier si un paramètre 'popup' existe dans l'URL et afficher la popup
  if (popupType) {
    let text = "";
    let redirectToLogin = false;

    if (popupType === "permis") {
      text = "Permis non valide : il doit avoir au moins 2 ans.";
    } else if (popupType === "email") {
      text = "Cet email est déjà utilisé.";
    } else if (popupType === "erreur") {
      text = "Erreur lors de l'inscription.";
    } else if (popupType === "succes") {
      text = "Inscription réussie ! Vous allez être redirigé.";
      redirectToLogin = true;
    }

    // Ajouter le texte à la popup et l'afficher
    message.textContent = text;
popup.className = popupType === "succes" ? "popup-succes" : "popup-erreur";
popup.style.display = "flex";


    // Fermer la popup après 4 secondes et rediriger si succès
    setTimeout(() => {
      popup.style.display = "none";
      if (redirectToLogin) {
        window.location.href = "login.html";  // Redirection vers login.php
      }
    }, 4000);
  }
};


  </script>
</head>
<body>
  <form method="post" action="traitement_inscription.php" onsubmit="return validerFormulaire()" autocomplete="off">
  <pre> .

  </pre> 
  <div class=""></div>  
  <div class="input-group">
      <label for="nom">Nom</label>
      <input type="text" id="nom" name="nom_client" required>
    </div>

    <div class="input-group">
      <label for="prenom">Prénom</label>
      <input type="text" id="prenom" name="prenom_client" required>
    </div>

    <div class="input-group">
      <label for="tel">Numéro de téléphone</label>
      <input type="tel" id="tel" name="num_tel" required>
    </div>

    <div class="input-group">
      <label for="adresse">Adresse</label>
      <input type="text" id="adresse" name="adresse" required>
    </div>

    <div class="input-group">
      <label for="permis">Numéro de permis</label>
      <input type="text" id="permis" name="num_permis" required>
    </div>

    <div class="input-group">
      <label for="date_permis">Date d'obtention du permis</label>
      <input type="date" id="date_permis" name="date_permis" required>
    </div>

    <div class="input-group">
      <label for="email">Email</label>
      <input type="email" id="email" name="email" required autocomplete="off">
    </div>

    <div class="input-group">
      <label for="mdp">Mot de passe</label>
      <input type="password" id="mdp" name="motdepasse_client" required autocomplete="new-password">
    </div>

    <div class="input-group">
      <label for="confirm_mdp">Confirmer le mot de passe</label>
      <input type="password" id="confirm_mdp" required autocomplete="new-password">
    </div>

    <button type="submit">S'inscrire</button>

    <div class="signup-text">
      <span>Déjà un compte ?</span>
      <a class="signup-link" href="login.html">Connexion</a>
    </div>
  </form>

  <!-- Popup de notification -->
  <div id="popup" class="" style="display:none;">
  <div id="popup-message"></div>
</div>

</body>
</html>

