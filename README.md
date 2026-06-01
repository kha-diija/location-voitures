# 🚗 CAR RENTAL — Système de Location de Voitures

> Application web complète de gestion de location de voitures, développée avec PHP, MySQL et JavaScript.

![PHP](https://img.shields.io/badge/PHP-8.3-777BB4?style=flat-square&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-9.1-4479A1?style=flat-square&logo=mysql&logoColor=white)
![HTML5](https://img.shields.io/badge/HTML5-E34F26?style=flat-square&logo=html5&logoColor=white)
![CSS3](https://img.shields.io/badge/CSS3-1572B6?style=flat-square&logo=css3&logoColor=white)
![JavaScript](https://img.shields.io/badge/JavaScript-F7DF1E?style=flat-square&logo=javascript&logoColor=black)
![License](https://img.shields.io/badge/License-MIT-green?style=flat-square)

---

## 📋 Table des matières

- [Aperçu](#-aperçu)
- [Fonctionnalités](#-fonctionnalités)
- [Architecture & Technologies](#-architecture--technologies)
- [Schéma de la base de données](#-schéma-de-la-base-de-données)
- [Structure du projet](#-structure-du-projet)
- [Installation](#-installation)
- [Configuration](#-configuration)
- [Utilisation](#-utilisation)
- [Flotte de véhicules](#-flotte-de-véhicules)
- [Captures d'écran](#-captures-décran)
- [Auteurs](#-auteurs)

---
<img width="463" height="225" alt="image" src="https://github.com/user-attachments/assets/66b0604c-58cc-45a8-8b40-2280fbf4a568" />


## 🌟 Aperçu

**CAR RENTAL** est une application web de gestion de location de véhicules qui permet à des clients de parcourir une flotte de voitures disponibles, de faire des réservations en ligne, et à des administrateurs de gérer l'ensemble des opérations (flotte, réservations, clients, amendes et factures).

Le projet couvre le cycle complet d'une location : de la réservation à la restitution du véhicule, en passant par la gestion des retards et des amendes.

---

## ✨ Fonctionnalités

### 👤 Espace Client
- **Inscription & Connexion** — Création de compte avec numéro de permis, authentification sécurisée par session PHP
- **Parcourir la flotte** — Consultation des véhicules disponibles avec photos, caractéristiques et tarifs
- **Réservation en ligne** — Sélection des dates de début et de fin, calcul automatique du tarif total
- **Suivi des réservations** — Historique complet des réservations avec statuts (`confirmée`, `annulée`)
- **Annulation de réservation** — Possibilité d'annuler une réservation en cours
- **Gestion du profil** — Modification des informations personnelles et réinitialisation du mot de passe

### 🛠️ Espace Administrateur
- **Tableau de bord** — Vue globale de l'activité (clients, véhicules, réservations, amendes)
- **Gestion de la flotte** — Ajout, modification et suppression de véhicules avec upload d'images
- **Gestion des réservations** — Confirmation, suivi et gestion des retours de véhicules
- **Gestion des clients** — Liste complète des clients inscrits avec leurs informations
- **Amendes** — Création d'amendes (retard, dégât, carburant…) liées aux retours, suppression
- **Facturation** — Génération automatique des factures à la restitution

---

## 🏗️ Architecture & Technologies

| Couche | Technologie |
|--------|------------|
| Backend | PHP 8.3 (PDO + MySQLi) |
| Base de données | MySQL 9.1 |
| Frontend | HTML5, CSS3, JavaScript (Vanilla) |
| Icônes | Font Awesome 6.4 |
| Serveur local | XAMPP / WAMP |
| Versioning | Git & GitHub |

**Patterns utilisés :**
- Architecture **MVC-like** (séparation logique métier / affichage)
- API **AJAX** pour les requêtes dynamiques (chargement de voitures, réservations, clients)
- **Sessions PHP** pour la gestion de l'authentification et des rôles (`client` / `admin`)
- **PDO & MySQLi** avec requêtes préparées (protection contre les injections SQL)

---

## 🗃️ Schéma de la base de données

La base de données `locationvoitures` contient **6 tables** :

```
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
```

### Tables détaillées

| Table | Description |
|-------|-------------|
| `client` | Informations des clients inscrits (permis, contact, authentification) |
| `administrateur` | Comptes administrateurs |
| `voiture` | Flotte de véhicules (immatriculation, marque, modèle, tarif/jour) |
| `reservation` | Réservations liant un client à un véhicule sur une période |
| `retour_voiture` | Enregistrement des retours avec calcul des jours de retard |
| `amende` | Amendes associées à un retour ou une réservation |
| `facture` | Factures générées à la clôture d'un retour |

---

## 📁 Structure du projet

```
location-voitures/
├── locationvoitures.sql          # Dump complet de la base de données
├── presentation-carrental.pdf    # Présentation du projet
├── rapportcarrantal.pdf          # Rapport technique
└── voitures/
    ├── index.php                 # Page d'accueil publique (vitrine)
    ├── login.html / login.php    # Page de connexion
    ├── signup.php                # Inscription client
    ├── traitement_inscription.php
    ├── reset_password.html / .php
    ├── logout.php
    │
    ├── client.php                # Dashboard client
    ├── reservation.php           # Formulaire de réservation
    ├── confirmation.php          # Confirmation de réservation
    ├── mes_reservations.php      # Historique réservations client
    ├── cancel_reservation.php    # Annulation de réservation
    ├── load_compte.php           # Gestion du profil client
    ├── update_profile.php        # Mise à jour profil
    │
    ├── admin.php                 # Dashboard administrateur
    ├── ajouter_voiture.php       # Ajout de véhicule
    ├── modifier_voiture.php      # Modification de véhicule
    ├── supprimer_voiture.php     # Suppression de véhicule
    ├── ajouter_amende.php        # Création d'amende
    ├── supprimer_amende.php      # Suppression d'amende
    │
    ├── get_voitures.php          # API : liste des voitures
    ├── get_voiture.php           # API : détail d'une voiture
    ├── get_reservations.php      # API : liste des réservations
    ├── get_clients.php           # API : liste des clients
    ├── get_amendes.php           # API : liste des amendes
    ├── load_voitures.php         # Chargement AJAX voitures
    ├── load_reservations.php     # Chargement AJAX réservations
    │
    ├── db.php                    # Connexion base de données
    ├── custom_alert.php          # Composant alerte personnalisée
    │
    ├── style.css                 # Styles page d'accueil
    ├── styles.css / styless.css  # Styles complémentaires
    ├── admin_styles.css          # Styles espace admin
    ├── css/client.css            # Styles espace client
    │
    ├── script.js                 # Scripts JS généraux
    ├── admin.js                  # Scripts JS admin
    ├── client-functions.js       # Fonctions JS client
    ├── js/client.js              # Scripts JS dashboard client
    │
    └── images/                   # Photos des véhicules (ABC001–ABC020)
```

---

## ⚙️ Installation

### Prérequis

- [XAMPP](https://www.apachefriends.org/) ou [WAMP](https://www.wampserver.com/) (PHP 8.x + MySQL)
- Navigateur web moderne

### Étapes

**1. Cloner le dépôt**
```bash
git clone https://github.com/kha-diija/location-voitures.git
cd location-voitures
```

**2. Copier les fichiers dans le répertoire web**
```bash
# Pour XAMPP (Windows)
cp -r voitures/ C:/xampp/htdocs/location-voitures/

# Pour XAMPP (Linux/Mac)
cp -r voitures/ /opt/lampp/htdocs/location-voitures/
```

**3. Importer la base de données**

- Démarrer Apache et MySQL depuis le panneau de contrôle XAMPP
- Ouvrir [phpMyAdmin](http://localhost/phpmyadmin)
- Créer une nouvelle base de données nommée `locationvoitures`
- Importer le fichier `locationvoitures.sql`

**4. Accéder à l'application**

Ouvrir le navigateur et aller sur :
```
http://localhost/location-voitures/
```

---

## 🔧 Configuration

Le fichier de connexion à la base de données est `voitures/db.php` :

```php
$host     = 'localhost';
$user     = 'root';
$password = '';               // Modifier si votre MySQL a un mot de passe
$database = 'locationvoitures';
```

Adapter ces valeurs selon votre environnement local si nécessaire.

---

## 🚀 Utilisation

### Connexion Administrateur

| Champ | Valeur |
|-------|--------|
| Email | `alamisami@gmail.com` |
| Mot de passe | `123` |

### Connexion Client (exemples)

| Email | Mot de passe |
|-------|-------------|
| `ryad.berrada@hotmail.com` | `aaaaaaaa` |

> ⚠️ Ces identifiants sont fournis à titre de démonstration. En production, il est impératif de hasher les mots de passe (ex. `password_hash()` / `password_verify()`).

---

## 🚘 Flotte de véhicules

La flotte comprend **20 véhicules** de différentes marques et catégories :

| Immatriculation | Marque | Modèle | Carburant | Prix/jour |
|-----------------|--------|--------|-----------|-----------|
| ABC001 | Toyota | Yaris | Essence | 250 MAD |
| ABC002 | Peugeot | 208 | Diesel | 230 MAD |
| ABC003 | Renault | Clio | Essence | 240 MAD |
| ABC004 | Hyundai | i20 | Essence | 220 MAD |
| ABC005 | Volkswagen | Golf | Diesel | 270 MAD |
| ABC006 | Ford | Fiesta | Essence | 210 MAD |
| ABC007 | Kia | Rio | Diesel | 220 MAD |
| ABC008 | Honda | Civic | Essence | 290 MAD |
| ABC009 | Nissan | Micra | Essence | 200 MAD |
| ABC010 | Citroën | C3 | Essence | 215 MAD |
| … | … | … | … | … |
| ABC020 | Citroën | C4 | Essence | 260 MAD |

---

##  Captures d'écran



** Page d'accueil **
<img width="635" height="360" alt="image" src="https://github.com/user-attachments/assets/b88c89b1-a2ea-43ca-abf5-566515e030ba" />
<img width="554" height="317" alt="image" src="https://github.com/user-attachments/assets/9bca7efc-77a8-4b34-8866-3a731c8334d2" />
<img width="532" height="316" alt="image" src="https://github.com/user-attachments/assets/2a0ccb16-6d02-4536-a503-d009a3afad0a" />


**Page de connexion**
<img width="554" height="316" alt="image" src="https://github.com/user-attachments/assets/2ba96482-0fd2-4e74-b1d2-609009a8079b" />

**Page d'inscription**

<img width="549" height="317" alt="image" src="https://github.com/user-attachments/assets/79d17af8-42eb-46f2-ac26-695c2c94fce7" />


**Réinitialisation du mot de passe**
<img width="542" height="316" alt="image" src="https://github.com/user-attachments/assets/06640cac-0fac-4477-b8f4-9ddf7e0a8846" />


**Dashboard client / Voitures disponibles**
<img width="557" height="320" alt="image" src="https://github.com/user-attachments/assets/67ca159f-75b0-4d3c-8763-51f648e92219" />

**Modal de réservation**
<img width="501" height="320" alt="image" src="https://github.com/user-attachments/assets/f0173329-f158-4c7c-be36-6199b71a8cdb" />

**Slide 15 — Mes Réservations**
<img width="555" height="315" alt="image" src="https://github.com/user-attachments/assets/a462ae1a-304e-4f66-81ae-dce23c12aadc" />



**Tableau de bord administrateur**
<img width="551" height="316" alt="image" src="https://github.com/user-attachments/assets/e10cdcb1-99e9-4d39-8fd3-32570fb94db3" />



---

## 📄 Documents du projet

| Document | Description |
|----------|-------------|
| [`presentation-carrental.pdf`](./presentation-carrental.pdf) | Présentation PowerPoint du projet (slides) |
| [`rapportcarrantal.pdf`](./rapportcarrantal.pdf) | Rapport technique détaillé |
| [`locationvoitures.sql`](./locationvoitures.sql) | Dump complet de la base de données |

---

## 👨‍💻 Auteurs

Projet réalisé dans le cadre d'un projet académique.

- **GitHub** : [@kha-diija](https://github.com/kha-diija)
- **Dépôt** : [github.com/kha-diija/location-voitures](https://github.com/kha-diija/location-voitures)

---

## 📝 Licence

Ce projet est distribué sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

---

<div align="center">
  <sub>Fait avec ❤️ — CAR RENTAL &copy; 2025</sub>
</div>
