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
