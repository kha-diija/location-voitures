<p align="center">
  <img src="https://media.giphy.com/media/v1.Y2lkPTc5MGI3NjExM3B0N3Znd2Y1N3M0NXpndXp5bXN0Ym15bW93bHpxbXN0Ym15bSZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/Qss0b96nS8UHi/giphy.gif" alt="MasterHead" width="100%">
</p>

<img align="left" src="https://user-images.githubusercontent.com/65187002/144930161-2f783401-8d27-4fdf-a2f7-cc0ba32f1f1f.gif" width="21%" />
<img align="right" src="https://user-images.githubusercontent.com/65187002/144930161-2f783401-8d27-4fdf-a2f7-cc0ba32f1f1f.gif" width="21%" />

<h1 align="center">🚗 CAR RENTAL — Système de Location de Voitures</h1>
<h3 align="center">💻 Application Web Full-Stack | PHP & MySQL | Architecture MVC-like</h3>

<p align="center">
  <b>CAR RENTAL</b> est une application web dynamique complète dédiée à la gestion globale d'une agence de location de voitures. L'application automatise le flux complet de réservation pour les clients tout en offrant une interface d'administration robuste pour piloter l'activité en temps réel (flotte, contrats, clients, retards et amendes).
</p>

<p align="center">
  <img src="https://img.shields.io/badge/PHP-8.x-777BB4?style=flat-square&logo=php&logoColor=white" />
  <img src="https://img.shields.io/badge/MySQL-9.x-4479A1?style=flat-square&logo=mysql&logoColor=white" />
  <img src="https://img.shields.io/badge/JavaScript-Vanilla-F7DF1E?style=flat-square&logo=javascript&logoColor=black" />
  <img src="https://img.shields.io/badge/Licence-MIT-green?style=flat-square" />
</p>

---

## 📋 Table des matières
- [🌟 Aperçu du Projet](#-aperçu-du-projet)
- [✨ Fonctionnalités Détaillées](#-fonctionnalités-détaillées)
- [🏗️ Architecture & Technologies](#️-architecture--technologies)
- [🗃️ Schéma de la Base de Données](#-schéma-de-la-base-de-données)
- [📁 Structure Complète du Projet](#-structure-complète-du-projet)
- [📸 Captures d'Écran & Interfaces](#-captures-décran--interfaces)
- [⚙️ Installation & Configuration](#️-installation--configuration)
- [🔧 Utilisation (Comptes de Test)](#-utilisation-comptes-de-test)
- [📄 Documents Intégrés](#-documents-intégrés)

---

## 🌟 Aperçu du Projet
Le projet couvre le cycle complet d'une location de véhicule : de la recherche d'une voiture disponible à l'inscription sécurisée, en passant par le calcul automatisé des tarifs selon la durée, jusqu'à la restitution du véhicule, la gestion des retards, l'application d'amendes et l'édition de factures de clôture.

---

## ✨ Fonctionnalités Détaillées

### 👤 1. Espace Client (Front-Office)
* **Inscription & Connexion :** Création de compte complète (nom, prénom, tél, adresse, numéro et date de permis) avec gestion sécurisée des sessions PHP.
* **Flotte Dynamique :** Consultation en temps réel des 20 véhicules de l'agence avec filtres, statuts de disponibilité et tarifs journaliers.
* **Réservation en Ligne :** Sélection des dates avec blocage automatique des dates indisponibles et calcul instantané du montant total.
* **Suivi du Compte :** Historique complet des réservations avec statuts (Confirmée, Annulée) et possibilité d'annulation en un clic.
* **Gestion du Profil :** Modification des données personnelles et réinitialisation sécurisée du mot de passe.

### 🛠️ 2. Espace Administrateur (Back-Office)
* **Tableau de Bord centralisé :** Vue globale instantanée sur l'activité (indicateurs clés : total clients, voitures, réservations et amendes).
* **Gestion du Parc Automobile (CRUD) :** Ajout, modification et suppression des véhicules avec téléversement et stockage des photos.
* **Suivi des Réservations :** Validation des demandes, suivi des départs et enregistrement des retours.
* **Gestion des Retards & Amendes :** Calcul automatique des jours de retard lors du retour et application d'amendes modulables (panne, dégâts matériels, retard).
* **Facturation Automatique :** Génération des fiches de facturation à la clôture du dossier.

---

## 🏗️ Architecture & Technologies

<p align="center">
  <img src="https://skillicons.dev/icons?i=php,mysql,html,css,js" height="50" />
</p>

* **Architecture MVC-like :** Séparation claire entre la logique métier (scripts de traitement backend PHP) et la couche de présentation (interfaces HTML/CSS).
* **API AJAX (JavaScript Asynchrone) :** Chargement dynamique des données sans rechargement de page pour la liste des voitures, des réservations et des clients (`get_voitures.php`, `load_reservations.php`, etc.).
* **Sécurité des Données :** Utilisation exclusive de l'API **PDO** et **MySQLi** avec requêtes préparées pour faire barrière aux injections SQL.

---

## 🗃️ Schéma de la Base de Données

La base de données relationnelle relationnelle `locationvoitures` est rigoureusement structurée pour maintenir une intégrité parfaite et empêcher les doubles réservations :

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
