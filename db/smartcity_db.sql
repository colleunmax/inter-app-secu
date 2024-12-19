-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 18, 2024 at 07:06 PM
-- Server version: 11.6.2-MariaDB-log
-- PHP Version: 8.1.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `smartcity_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alertes_globales`
--

CREATE TABLE `alertes_globales` (
  `id_alerte` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `niveau` int(11) NOT NULL,
  `date_creation` date NOT NULL DEFAULT curdate(),
  `statut` tinyint(1) NOT NULL,
  `id_capteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `capteurs`
--

CREATE TABLE `capteurs` (
  `id_capteur` int(11) NOT NULL,
  `nom_capteur` varchar(128) NOT NULL,
  `departement` varchar(255) NOT NULL,
  `statut` int(11) NOT NULL,
  `date_derniere_maj` datetime NOT NULL DEFAULT current_timestamp(),
  `type_capteur` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `capteurs`
--

INSERT INTO `capteurs` (`id_capteur`, `nom_capteur`, `departement`, `statut`, `date_derniere_maj`, `type_capteur`) VALUES
(1, 'Capteur Sécurité Entrée', 'Sécurité', 3, '2024-12-17 16:10:58', 3),
(2, 'Capteur Sécurité Couloir', 'Sécurité', 3, '2024-12-17 16:10:58', 3),
(3, 'Capteur Sécurité Parking', 'Sécurité', 3, '2024-12-17 16:10:58', 3);

-- --------------------------------------------------------

--
-- Table structure for table `rapports_globales`
--

CREATE TABLE `rapports_globales` (
  `id_rapport` int(11) NOT NULL,
  `departement` varchar(10) NOT NULL,
  `periode` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_creation` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `statistiques_globales`
--

CREATE TABLE `statistiques_globales` (
  `id_statistique` int(11) NOT NULL,
  `departement` varchar(10) NOT NULL,
  `type_statistique` varchar(16) NOT NULL,
  `valeur` int(11) NOT NULL,
  `date_enregistrement` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `type_capteur`
--

CREATE TABLE `type_capteur` (
  `id_type_capteur` int(11) NOT NULL,
  `type` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `type_capteur`
--

INSERT INTO `type_capteur` (`id_type_capteur`, `type`) VALUES
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
  ADD KEY `fk_alertes_globales_capteur` (`id_capteur`);

--
-- Indexes for table `capteurs`
--
ALTER TABLE `capteurs`
  ADD PRIMARY KEY (`id_capteur`),
  ADD KEY `fk_capteurs_type_capteur` (`type_capteur`);

--
-- Indexes for table `rapports_globales`
--
ALTER TABLE `rapports_globales`
  ADD PRIMARY KEY (`id_rapport`);

--
-- Indexes for table `statistiques_globales`
--
ALTER TABLE `statistiques_globales`
  ADD PRIMARY KEY (`id_statistique`);

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
  MODIFY `id_alerte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `capteurs`
--
ALTER TABLE `capteurs`
  MODIFY `id_capteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `rapports_globales`
--
ALTER TABLE `rapports_globales`
  MODIFY `id_rapport` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `statistiques_globales`
--
ALTER TABLE `statistiques_globales`
  MODIFY `id_statistique` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `type_capteur`
--
ALTER TABLE `type_capteur`
  MODIFY `id_type_capteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alertes_globales`
--
ALTER TABLE `alertes_globales`
  ADD CONSTRAINT `fk_alertes_globales_capteur` FOREIGN KEY (`id_capteur`) REFERENCES `capteurs` (`id_capteur`);

--
-- Constraints for table `capteurs`
--
ALTER TABLE `capteurs`
  ADD CONSTRAINT `fk_capteurs_type_capteur` FOREIGN KEY (`type_capteur`) REFERENCES `type_capteur` (`id_type_capteur`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
