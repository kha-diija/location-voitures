-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : dim. 01 juin 2025 à 10:45
-- Version du serveur : 9.1.0
-- Version de PHP : 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `locationvoitures`
--

-- --------------------------------------------------------

--
-- Structure de la table `administrateur`
--

DROP TABLE IF EXISTS `administrateur`;
CREATE TABLE IF NOT EXISTS `administrateur` (
  `id_admin` int NOT NULL AUTO_INCREMENT,
  `nom_admin` varchar(50) DEFAULT NULL,
  `prenom_admin` varchar(50) DEFAULT NULL,
  `email_admin` varchar(100) DEFAULT NULL,
  `motdepasse_admin` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_admin`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `administrateur`
--

INSERT INTO `administrateur` (`id_admin`, `nom_admin`, `prenom_admin`, `email_admin`, `motdepasse_admin`) VALUES
(1, 'alami', 'sami', 'alamisami@gmail.com', '123');

-- --------------------------------------------------------

--
-- Structure de la table `amende`
--

DROP TABLE IF EXISTS `amende`;
CREATE TABLE IF NOT EXISTS `amende` (
  `id_amende` int NOT NULL AUTO_INCREMENT,
  `type_amende` varchar(50) DEFAULT NULL,
  `description` text,
  `montant` decimal(10,2) DEFAULT NULL,
  `id_retour` int DEFAULT NULL,
  `num_reser` int DEFAULT NULL,
  PRIMARY KEY (`id_amende`),
  KEY `id_retour` (`id_retour`),
  KEY `fk_amende_reservation` (`num_reser`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `client`
--

DROP TABLE IF EXISTS `client`;
CREATE TABLE IF NOT EXISTS `client` (
  `id_client` int NOT NULL AUTO_INCREMENT,
  `nom_client` varchar(100) DEFAULT NULL,
  `prenom_client` varchar(100) DEFAULT NULL,
  `num_tel` varchar(20) DEFAULT NULL,
  `adresse` text,
  `num_permis` varchar(50) DEFAULT NULL,
  `date_permis` date DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `motdepasse_client` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_client`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `client`
--

INSERT INTO `client` (`id_client`, `nom_client`, `prenom_client`, `num_tel`, `adresse`, `num_permis`, `date_permis`, `email`, `motdepasse_client`) VALUES
(2, 'EL HaBIB', 'ahmed', '064523981265', 'QSD45F6G', '1234567', '2021-02-10', 'ahmed.elhabib@gmail.com', 'aaaa'),
(3, 'berrada', 'ryad', '063537378192', 'DFGH', '123456', '2022-02-08', 'ryad.berrada@hotmail.com', 'aaaaaaaa'),
(4, 'oujdi', 'yassin', '068473163984', 'vhdb', '4636', '0000-00-00', 'yassine.oujdi@gmail.com', 'qqqqqq'),
(5, 'wakili', 'sarah', '071982736459', 'drftgyhuj', '1234567', '2019-12-30', 'sarah.wakili@gmail.com', 'azertyu1'),
(7, 'najib', 'khalid', '063829163548', 'DFVBFD', '4R345', '2000-02-23', 'khalid.najib@yahoo.com', 'a'),
(8, 'el azizi', 'sofia', '064523981265', 'SDVDWSER', '234534', '2000-11-21', 'sofia.elazizi@gmail.com', 'a'),
(9, 'toumi', 'sami', '23426464', 'EZFERF', '234234', '2000-12-12', 'sami.toumi@gmail.com', 'a'),
(11, 'mhaili', 'khadija', '123456781212', 'ZSEDRFTGY456', '2345678', '2021-12-28', 'khadijajhs@gmail.com', 'aaaa'),
(12, 'mhaili', 'khadija', '1234567812', 'ZSEDRFTGY456', '2345678', '2021-12-28', 'khadijajhsaz@gmail.com', 'aaaa');

-- --------------------------------------------------------

--
-- Structure de la table `facture`
--

DROP TABLE IF EXISTS `facture`;
CREATE TABLE IF NOT EXISTS `facture` (
  `num_facture` int NOT NULL AUTO_INCREMENT,
  `date_facture` date DEFAULT NULL,
  `montant_total` decimal(10,2) DEFAULT NULL,
  `id_retour` int DEFAULT NULL,
  PRIMARY KEY (`num_facture`),
  KEY `id_retour` (`id_retour`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `reservation`
--

DROP TABLE IF EXISTS `reservation`;
CREATE TABLE IF NOT EXISTS `reservation` (
  `num_reser` int NOT NULL AUTO_INCREMENT,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `statut` varchar(20) DEFAULT NULL,
  `tarif` decimal(10,2) DEFAULT NULL,
  `id_client` int DEFAULT NULL,
  `num_immatriculation` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`num_reser`),
  KEY `id_client` (`id_client`),
  KEY `num_immatriculation` (`num_immatriculation`)
) ;

--
-- Déchargement des données de la table `reservation`
--

INSERT INTO `reservation` (`num_reser`, `date_debut`, `date_fin`, `statut`, `tarif`, `id_client`, `num_immatriculation`) VALUES
(31, '2025-05-08', '2025-05-22', 'confirmée', 3220.00, 2, 'ABC002'),
(32, '2025-05-10', '2025-05-30', 'confirmée', 4800.00, 2, 'ABC003'),
(33, '2025-05-30', '2025-06-07', 'confirmée', 2000.00, 9, 'ABC001'),
(34, '2025-05-31', '2025-06-07', 'confirmée', 1610.00, 9, 'ABC002'),
(35, '2025-05-30', '2025-06-05', 'confirmée', 1620.00, 9, 'ABC005'),
(36, '2025-05-31', '2025-06-07', 'confirmée', 2030.00, 11, 'ABC008');

-- --------------------------------------------------------

--
-- Structure de la table `retour_voiture`
--

DROP TABLE IF EXISTS `retour_voiture`;
CREATE TABLE IF NOT EXISTS `retour_voiture` (
  `id_retour` int NOT NULL AUTO_INCREMENT,
  `date_retour` date DEFAULT NULL,
  `retard_jours` int DEFAULT NULL,
  `num_reser` int DEFAULT NULL,
  PRIMARY KEY (`id_retour`),
  KEY `num_reser` (`num_reser`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Structure de la table `voiture`
--

DROP TABLE IF EXISTS `voiture`;
CREATE TABLE IF NOT EXISTS `voiture` (
  `num_immatriculation` varchar(20) NOT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `modele` varchar(50) DEFAULT NULL,
  `carburant` varchar(20) DEFAULT NULL,
  `statut_voit` varchar(20) DEFAULT NULL,
  `kilometrage` int DEFAULT NULL,
  `prix_location` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`num_immatriculation`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `voiture`
--

INSERT INTO `voiture` (`num_immatriculation`, `marque`, `modele`, `carburant`, `statut_voit`, `kilometrage`, `prix_location`) VALUES
('ABC001', 'Toyota', 'Yaris', 'Essence', 'disponible', 30000, 250.00),
('ABC002', 'Peugeot', '208', 'Diesel', 'disponible', 45000, 230.00),
('ABC003', 'Renault', 'Clio', 'Essence', 'disponible', 40000, 240.00),
('ABC004', 'Hyundai', 'i20', 'Essence', 'disponible', 32000, 220.00),
('ABC005', 'Volkswagen', 'Golf', 'Diesel', 'disponible', 50000, 270.00),
('ABC006', 'Ford', 'Fiesta', 'Essence', 'disponible', 35000, 210.00),
('ABC007', 'Kia', 'Rio', 'Diesel', 'disponible', 37000, 220.00),
('ABC008', 'Honda', 'Civic', 'Essence', 'disponible', 42000, 290.00),
('ABC009', 'Nissan', 'Micra', 'Essence', 'disponible', 31000, 200.00),
('ABC010', 'Citroen', 'C3', 'Essence', 'disponible', 33000, 215.00),
('ABC011', 'Toyota', 'Corolla', 'Essence', 'disponible', 36000, 275.00),
('ABC012', 'Peugeot', '308', 'Diesel', 'disponible', 48000, 260.00),
('ABC013', 'Renault', 'Megane', 'Diesel', 'disponible', 45000, 265.00),
('ABC014', 'Hyundai', 'i30', 'Essence', 'disponible', 39000, 250.00),
('ABC015', 'Volkswagen', 'Polo', 'Essence', 'disponible', 34000, 240.00),
('ABC016', 'Ford', 'Focus', 'Diesel', 'disponible', 43000, 255.00),
('ABC017', 'Kia', 'Ceed', 'Essence', 'disponible', 37000, 235.00),
('ABC018', 'Honda', 'Jazz', 'Essence', 'disponible', 31000, 225.00),
('ABC019', 'Nissan', 'Juke', 'Diesel', 'disponible', 46000, 270.00),
('ABC020', 'Citroen', 'C4', 'Essence', 'disponible', 39000, 260.00);

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `amende`
--
ALTER TABLE `amende`
  ADD CONSTRAINT `amende_ibfk_1` FOREIGN KEY (`id_retour`) REFERENCES `retour_voiture` (`id_retour`),
  ADD CONSTRAINT `fk_amende_reservation` FOREIGN KEY (`num_reser`) REFERENCES `reservation` (`num_reser`) ON DELETE CASCADE;

--
-- Contraintes pour la table `facture`
--
ALTER TABLE `facture`
  ADD CONSTRAINT `facture_ibfk_1` FOREIGN KEY (`id_retour`) REFERENCES `retour_voiture` (`id_retour`);

--
-- Contraintes pour la table `reservation`
--
ALTER TABLE `reservation`
  ADD CONSTRAINT `reservation_ibfk_1` FOREIGN KEY (`id_client`) REFERENCES `client` (`id_client`),
  ADD CONSTRAINT `reservation_ibfk_2` FOREIGN KEY (`num_immatriculation`) REFERENCES `voiture` (`num_immatriculation`);

--
-- Contraintes pour la table `retour_voiture`
--
ALTER TABLE `retour_voiture`
  ADD CONSTRAINT `retour_voiture_ibfk_1` FOREIGN KEY (`num_reser`) REFERENCES `reservation` (`num_reser`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
