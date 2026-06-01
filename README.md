# 🚗 Plateforme de Gestion & de Réservation de Véhicules

<p align="center">
  <img src="https://skillicons.dev/icons?i=php,mysql,html,css,js" alt="Tech Stack" />
</p>

---

## 📝 À propos du projet
Ce projet consiste en la conception et le développement d'une application web dynamique complète dédiée à la **gestion globale d'une agence de location de voitures**[cite: 1, 2]. L'application automatise le flux complet de réservation pour les clients tout en offrant une interface d'administration robuste pour piloter l'activité de l'agence.

### 🎯 Objectifs clés :
* Offrir une expérience utilisateur fluide pour la recherche et la réservation de véhicules[cite: 1, 2].
* Centraliser la gestion de la flotte automobile, des clients et des contrats[cite: 1, 2].
* Assurer une persistance et une intégrité parfaites des données avec une base relationnelle.

---

## 📂 Contenu du Dépôt
Ce dépôt est organisé de manière transparente pour inclure tout le cycle de vie du projet :
* 💻 **`voitures/`** — Le code source complet de l'application (PHP, JS, CSS).
* 🗃️ **`locationvoitures.sql`** — Le script de structure et d'initialisation de la base de données.
* 📄 **`rapportcarrantal.pdf`** — Le rapport conceptuel et technique détaillé.
* 📊 **`presentation-carrental.pdf`** — Le support visuel de présentation du projet.

---

## 🛠️ Stack Technique

### 👨‍💻 Langages de programmation
<p align="left">
  <img src="https://skillicons.dev/icons?i=php,js,html,css" height="40" />
</p>

### ⚙️ Backend & Base de données
<p align="left">
  <img src="https://skillicons.dev/icons?i=mysql" height="40" />
</p>

---

## 📐 Modèle Logique des Données (MLD)
La base de données relationnelle a été rigoureusement modélisée (tables `client`, `voiture`, `reservation`, `amende`, `administrateur`) pour garantir l'intégrité des informations et éviter les conflits de dates lors des réservations.

<p align="center">
  <!-- PLACE ICI L'IMAGE DU MLD (Page 8 du Rapport ou Diapo 7 de la Présentation) -->
  <img src="images/mld.png" alt="Modèle Logique des Données" width="80%">
</p>

---

## ✨ Interfaces de l'Application

### 👤 1. Espace Client (Public)
L'interface client offre un parcours utilisateur fluide, de la découverte de la flotte jusqu'à la génération de la facture de réservation.

* **Page d'Accueil & Flotte :** Une vitrine moderne présentant les services et les véhicules disponibles avec leurs tarifs.
<p align="center">
  <!-- PLACE ICI L'IMAGE DE L'ACCUEIL CLIENT (Page 8 du Rapport ou Diapo 8 de la Présentation) -->
  <img src="images/accueil_client.png" alt="Accueil Client" width="45%">
  <!-- PLACE ICI L'IMAGE DE LA FLOTTE (Page 9 du Rapport ou Diapo 11 de la Présentation) -->
  <img src="images/flotte_voitures.png" alt="Flotte de véhicules" width="45%">
</p>

* **Réservation & Facturation :** Sélection dynamique des dates, formulaire de paiement sécurisé et édition automatisée de la facture au format PDF.
<p align="center">
  <!-- PLACE ICI L'IMAGE DU FORMULAIRE DE RÉSERVATION (Page 16 du Rapport) -->
  <img src="images/reservation_form.png" alt="Formulaire de Réservation" width="45%">
  <!-- PLACE ICI L'IMAGE DE LA FACTURE GÉNÉRÉE (Page 17 ou 18 du Rapport) -->
  <img src="images/facture_pdf.png" alt="Facture PDF Générée" width="45%">
</p>

---

### 👑 2. Panneau d'Administration (Back-Office)
L'espace administrateur permet un contrôle total et sécurisé sur l'ensemble de l'activité de l'agence.

* **Tableau de Bord & Statistiques :** Vue d'ensemble du nombre de voitures, réservations actives, clients inscrits et amendes.
<p align="center">
  <!-- PLACE ICI L'IMAGE DU DASHBOARD ADMIN (Page 10 du Rapport ou Diapo 13 de la Présentation) -->
  <img src="images/dashboard_admin.png" alt="Tableau de Bord Administrateur" width="80%">
</p>

* **Gestion du Parc Automobile (CRUD) :** Interface permettant d'ajouter, modifier, supprimer et filtrer les véhicules en temps réel selon plusieurs critères (marque, immatriculation, prix, etc.)[cite: 2].
<p align="center">
  <!-- PLACE ICI L'IMAGE DE LA LISTE DES VOITURES (Page 10 du Rapport) -->
  <img src="images/liste_voitures.png" alt="Gestion de la Flotte" width="80%">
</p>

* **Gestion des Réservations & Amendes :** Suivi rigoureux des contrats de location et application d'amendes modulables en cas de retard ou de véhicule endommagé[cite: 2].
<p align="center">
  <!-- PLACE ICI L'IMAGE DE LA GESTION DES AMENDES (Page 14 du Rapport) -->
  <img src="images/gestion_amendes.png" alt="Gestion des Amendes" width="80%">
</p>

---

## 🚀 Installation et Lancement en local

1. **Cloner le projet :**
```bash
   git clone [https://github.com/kha-diija/location-voitures.git](https://github.com/kha-diija/location-voitures.git)
