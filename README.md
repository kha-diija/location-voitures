<p align="center">
  <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExM3B0N3Znd2Y1N3M0NXpndXp5bXN0Ym15bW93bHpxbXN0Ym15bSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/Qss0b96nS8UHi/giphy.gif" alt="Car Rental Banner" width="100%">
</p>

<h1 align="center">🚗 CAR RENTAL — Système de Location de Voitures</h1>
<h3 align="center">Application Web Full-Stack | PHP & MySQL | Architecture MVC-like</h3>

<p align="center">
  <b>CAR RENTAL</b> est une application dynamique de gestion de location de véhicules qui automatise le flux complet de réservation pour les clients tout en offrant un panneau d'administration robuste pour piloter l'activité de l'agence (flotte, contrats, clients, retards et amendes).
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-9.x-4479A1?style=flat-square&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=flat-square&logo=javascript&logoColor=black" />
  <img src="https://img.shields.io/badge/Licence-MIT-green?style=flat-square" />
</p>

---

## 📋 Table des matières
* [🌟 Aperçu du Projet](#-aperçu-du-projet)
* [✨ Fonctionnalités Détaillées](#-fonctionnalités-détaillées)
* [🏗️ Architecture & Technologies](#️-architecture--technologies)
* [🗃️ Schéma de la Base de Données](#-schéma-de-la-base-de-données)
* [📁 Structure du Projet](#-structure-du-projet)
* [📸 Captures d'Écran](#-captures-décran)
* [⚙️ Installation & Configuration](#️-installation--configuration)
* [🔧 Utilisation (Comptes de Test)](#-utilisation-comptes-de-test)
* [📄 Documents Intégrés](#-documents-intégrés)

---

## 🌟 Aperçu du Projet
Le projet couvre le cycle complet d'une location de véhicule : de la recherche d'une voiture disponible à l'inscription sécurisée, en passant par le calcul automatisé des tarifs selon la durée, jusqu'à la restitution du véhicule, la gestion des retards, l'application d'amendes et l'édition de factures de clôture.

---

## ✨ Fonctionnalités Détaillées

### 👤 Espace Client (Front-Office)
* **Inscription & Connexion :** Création de compte complète (permis, contact) et authentification sécurisée par sessions PHP.
* **Flotte Dynamique :** Consultation en temps réel des véhicules de l'agence avec caractéristiques et tarifs journaliers.
* **Réservation en Ligne :** Sélection des dates avec calcul instantané et automatisé du montant total.
* **Suivi & Annulation :** Historique complet des réservations avec statuts (Confirmée, Annulée) et possibilité d'annulation.
* **Gestion du Profil :** Modification des données personnelles et réinitialisation du mot de passe.

### 🛠️ Espace Administrateur (Back-Office)
* **Tableau de Bord centralisé :** Vue globale instantanée sur les indicateurs clés (total clients, voitures, réservations et amendes).
* **Gestion du Parc Automobile (CRUD) :** Ajout, modification et suppression des véhicules avec téléversement des photos.
* **Suivi des Réservations :** Validation des demandes, suivi des départs et enregistrement des retours.
* **Gestion des Retards & Amendes :** Calcul automatique des jours de retard lors du retour et application d'amendes modulables.
* **Facturation Automatique :** Génération des fiches de facturation à la clôture du dossier.

---

## 🏗️ Architecture & Technologies

<p align="center">
  <img src="https://skillicons.dev/icons?i=php,mysql,html,css,js,git,github" height="45" />
</p>

* **Architecture MVC-like :** Séparation claire entre la logique métier (scripts PHP backend) et la couche de présentation (interfaces HTML/CSS).
* **API AJAX (JavaScript) :** Chargement dynamique et asynchrone des données pour fluidifier l'expérience utilisateur.
* **Sécurité :** Utilisation exclusive de l'API **PDO** et **MySQLi** avec requêtes préparées contre les injections SQL.

---

## 🗃️ Schéma de la Base de Données

La base de données relationnelle `locationvoitures` est structurée ainsi pour maintenir une intégrité parfaite :

```text
┌─────────────────┐       ┌──────────────────────┐       ┌──────────────┐
│    client        │       │      reservation      │       │   voiture    │
│─────────────────│       │──────────────────────│       │──────────────│
│ id_client (PK)  │──┐    │ num_reser (PK)        │   ┌──│num_immat(PK) │
│ nom_client      │  └───>│ id_client (FK)        │   │  │ marque       │
│ prenom_client   │       │ num_immatriculation(FK│───┘  │ modele       │
│ num_tel         │       │ date_debut            │       │ carburant    │
│ adresse         │       │ date_fin              │       │ statut_voit  │
│ num_permis      │       │ statut                │       │ kilometrage  │
│ date_permis     │       │ tarif                 │       │ prix_location│
│ email           │       └──────────┬────────────┘       └──────────────┘
│ motdepasse      │                  │
└─────────────────┘                  │
                              ┌──────▼──────────┐
                              │  retour_voiture  │
┌─────────────────┐           │─────────────────│       ┌──────────────┐
│ administrateur  │           │ id_retour (PK)   │──┬──>│   facture    │
│─────────────────│           │ date_retour      │  │   │──────────────│
│ id_admin (PK)   │           │ retard_jours     │  │   │ num_facture  │
│ nom_admin       │           │ num_reser (FK)   │  │   │ date_facture │
│ prenom_admin    │           └──────┬───────────┘  │   │ montant_total│
│ email_admin     │                  │              │   └──────────────┘
│ motdepasse_admin│           ┌──────▼───────────┐  │
└─────────────────┘           │     amende        │──┘
                              │──────────────────│
                              │ id_amende (PK)   │
                              │ type_amende      │
                              │ description      │
                              │ montant          │
                              │ id_retour (FK)   │
                              │ num_reser (FK)   │
                              └──────────────────┘


📁 Structure du ProjetPlaintextlocation-voitures/
├── locationvoitures.sql          # Dump SQL complet de la base de données
├── presentation-carrental.pdf    # Support visuel de soutenance (Slides)
├── rapportcarrantal.pdf          # Rapport de conception et technique écrit
└── voitures/                     # Code source de l'application web
    ├── index.php                 # Page d'accueil publique de la plateforme
    ├── login.html / login.php    # Interface et traitement d'authentification
    ├── signup.php                # Formulaire d'inscription client
    ├── traitement_inscription.php# Script de validation d'inscription
    ├── reset_password.html / .php# Réinitialisation de mot de passe
    ├── logout.php                # Déconnexion et destruction de session
    ├── client.php                # Dashboard principal de l'espace client
    ├── reservation.php           # Traitement des demandes de location
    ├── mes_reservations.php      # Historique et annulation des réservations client
    ├── admin.php                 # Dashboard central d'administration (Statistiques)
    ├── ajouter_voiture.php       # Formulaire d'ajout d'un véhicule (CRUD)
    ├── modifier_voiture.php      # Formulaire de mise à jour d'un véhicule (CRUD)
    ├── supprimer_voiture.php     # Script de suppression d'un véhicule (CRUD)
    ├── ajouter_amende.php        # Formulaire d'affectation d'une amende de retour
    ├── db.php                    # Connexion globale PDO à la base de données
    └── images/                   # Dossier contenant les photos du parc (ABC001 à ABC020)
📸 Captures d'Écran💡 Remplacez les attributs src="..." par les liens réels de vos captures d'écran sur GitHub.👤 Espace ClientInterface Publique & Catalogue (Flotte Automobile)Formulaire de Réservation & Facture PDF Finale🛠️ Panneau AdministrateurTableau de Bord Principal & Statistiques de l'AgenceGestion de la Flotte (CRUD) & Formulaire des Amendes⚙️ Installation & ConfigurationÉtapes de déploiement en localClonage du dépôt distant :Bashgit clone [https://github.com/kha-diija/location-voitures.git](https://github.com/kha-diija/location-voitures.git)
cd location-voitures
Transfert vers le répertoire web :Copiez le répertoire voitures/ dans le dossier de publication de votre serveur local (ex: C:/xampp/htdocs/location-voitures/).Importation de la base de données :Créez une nouvelle base de données nommée locationvoitures sur phpMyAdmin.Importez-y le fichier locationvoitures.sql situé à la racine du projet.Fichier de Configuration (voitures/db.php)PHP$host     = 'localhost';
$user     = 'root';
$password = '';               // Modifiez selon votre configuration MySQL locale
$database = 'locationvoitures';
Accès à l'application via le navigateur : http://localhost/location-voitures/🔧 Utilisation (Comptes de Test)Utilisez ces profils de démonstration pré-configurés pour tester l'application en local :RôleAdresse EmailMot de passeAdministrateuralamisami@gmail.com123Client (Exemple 1)ahmed.elhabib@gmail.comaaaaClient (Exemple 2)sarah.wakili@gmail.comazertyu1📄 Documents IntégrésDocumentTypeDescription📄 rapportcarrantal.pdfRapport techniqueDocumentation d'ingénierie écrite complète détaillant la problématique, les objectifs et la conception.📊 presentation-carrental.pdfSlides de SoutenanceSupport visuel de présentation utilisé lors de l'exposé devant le jury.🗃️ locationvoitures.sqlScript SQLScript d'initialisation de la structure de données et insertion du jeu de test de la flotte.
