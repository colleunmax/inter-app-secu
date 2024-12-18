-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2024 at 03:45 PM
-- Server version: 8.0.30
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `security_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alertes_globales`
--

CREATE TABLE `alertes_globales` (
  `id_alerte` int NOT NULL,
  `id_capteur` int DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `niveau` enum('faible','moyen','critique') NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en attente','résolu') DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `alertes_locales`
--

CREATE TABLE `alertes_locales` (
  `id_alerte` int NOT NULL,
  `id_camera` int DEFAULT NULL,
  `description` varchar(255) NOT NULL,
  `date_signalement` datetime DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('en attente','résolu') DEFAULT 'en attente'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caméras`
--

CREATE TABLE `caméras` (
  `id_camera` int NOT NULL,
  `emplacement` varchar(255) NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '1',
  `date_maj` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `caméras`
--

INSERT INTO `caméras` (`id_camera`, `emplacement`, `statut`, `date_maj`) VALUES
(1, 'Entrée Principale', 1, '2024-12-17 16:10:58'),
(2, 'Couloir Nord', 1, '2024-12-17 16:10:58'),
(3, 'Parking Sous-sol', 1, '2024-12-17 16:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `capteurs`
--

CREATE TABLE `capteurs` (
  `id_capteur` int NOT NULL,
  `nom_capteur` varchar(255) NOT NULL,
  `type_capteur` int DEFAULT NULL,
  `departement` varchar(255) DEFAULT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT '0',
  `date_derniere_maj` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `capteurs`
--

INSERT INTO `capteurs` (`id_capteur`, `nom_capteur`, `type_capteur`, `departement`, `statut`, `date_derniere_maj`) VALUES
(1, 'Capteur Sécurité Entrée', 3, 'Sécurité', 3, '2024-12-17 16:10:58'),
(2, 'Capteur Sécurité Couloir', 3, 'Sécurité', 3, '2024-12-17 16:10:58'),
(3, 'Capteur Sécurité Parking', 3, 'Sécurité', 3, '2024-12-17 16:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `capteurs_intrusion`
--

CREATE TABLE `capteurs_intrusion` (
  `id_capteur` int NOT NULL,
  `emplacement` varchar(255) NOT NULL,
  `niveau_alerte` tinyint(1) NOT NULL DEFAULT '0',
  `date_signalement` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `capteurs_intrusion`
--

INSERT INTO `capteurs_intrusion` (`id_capteur`, `emplacement`, `niveau_alerte`, `date_signalement`) VALUES
(1, 'Entrée Principale', 0, '2024-12-17 16:10:58'),
(2, 'Couloir Nord', 0, '2024-12-17 16:10:58'),
(3, 'Parking Sous-sol', 0, '2024-12-17 16:10:58');

-- --------------------------------------------------------

--
-- Table structure for table `rapports_globales`
--

CREATE TABLE `rapports_globales` (
  `id_rapport` int NOT NULL,
  `departement` varchar(255) NOT NULL,
  `periode` enum('quotidienne','hebdomadaire') NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_creation` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_capteur`
--

CREATE TABLE `type_capteur` (
  `id_type_capteur` int NOT NULL,
  `Type` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `type_capteur`
--

INSERT INTO `type_capteur` (`id_type_capteur`, `Type`) VALUES
(1, 'trafic'),
(2, 'énergie'),
(3, 'sécurité'),
(4, 'eau');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alertes_globales`
--
ALTER TABLE `alertes_globales`
  ADD PRIMARY KEY (`id_alerte`),
  ADD KEY `id_capteur` (`id_capteur`);

--
-- Indexes for table `alertes_locales`
--
ALTER TABLE `alertes_locales`
  ADD PRIMARY KEY (`id_alerte`),
  ADD KEY `id_camera` (`id_camera`);

--
-- Indexes for table `caméras`
--
ALTER TABLE `caméras`
  ADD PRIMARY KEY (`id_camera`);

--
-- Indexes for table `capteurs`
--
ALTER TABLE `capteurs`
  ADD PRIMARY KEY (`id_capteur`),
  ADD KEY `type_capteur` (`type_capteur`);

--
-- Indexes for table `capteurs_intrusion`
--
ALTER TABLE `capteurs_intrusion`
  ADD PRIMARY KEY (`id_capteur`);

--
-- Indexes for table `rapports_globales`
--
ALTER TABLE `rapports_globales`
  ADD PRIMARY KEY (`id_rapport`);

--
-- Indexes for table `type_capteur`
--
ALTER TABLE `type_capteur`
  ADD PRIMARY KEY (`id_type_capteur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alertes_globales`
--
ALTER TABLE `alertes_globales`
  MODIFY `id_alerte` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `alertes_locales`
--
ALTER TABLE `alertes_locales`
  MODIFY `id_alerte` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caméras`
--
ALTER TABLE `caméras`
  MODIFY `id_camera` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `capteurs`
--
ALTER TABLE `capteurs`
  MODIFY `id_capteur` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rapports_globales`
--
ALTER TABLE `rapports_globales`
  MODIFY `id_rapport` int NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alertes_globales`
--
ALTER TABLE `alertes_globales`
  ADD CONSTRAINT `alertes_globales_ibfk_1` FOREIGN KEY (`id_capteur`) REFERENCES `capteurs` (`id_capteur`);

--
-- Constraints for table `alertes_locales`
--
ALTER TABLE `alertes_locales`
  ADD CONSTRAINT `alertes_locales_ibfk_1` FOREIGN KEY (`id_camera`) REFERENCES `caméras` (`id_camera`);

--
-- Constraints for table `capteurs`
--
ALTER TABLE `capteurs`
  ADD CONSTRAINT `capteurs_ibfk_1` FOREIGN KEY (`type_capteur`) REFERENCES `type_capteur` (`id_type_capteur`);

--
-- Constraints for table `capteurs_intrusion`
--
ALTER TABLE `capteurs_intrusion`
  ADD CONSTRAINT `capteurs_intrusion_ibfk_1` FOREIGN KEY (`id_capteur`) REFERENCES `capteurs` (`id_capteur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
