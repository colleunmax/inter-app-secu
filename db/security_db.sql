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
-- Database: `security_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `alertes_locales`
--

CREATE TABLE `alertes_locales` (
  `id_alerte` int(11) NOT NULL,
  `id_camera` int(11) NOT NULL,
  `description` varchar(255) NOT NULL,
  `date_signalement` date DEFAULT curdate(),
  `statut` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `caméras`
--

CREATE TABLE `caméras` (
  `id_camera` int(11) NOT NULL,
  `emplacement` varchar(255) NOT NULL,
  `statut` tinyint(1) NOT NULL DEFAULT 0,
  `date_maj` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `caméras`
--

INSERT INTO `caméras` (`id_camera`, `emplacement`, `statut`, `date_maj`) VALUES
(1, 'Entrée principale', 1, '2024-12-18'),
(2, 'Parking sous-terrain', 1, '2024-12-17'),
(3, 'Couloir des bureaux', 1, '2024-12-16');

-- --------------------------------------------------------

--
-- Table structure for table `capteurs_intrusion`
--

CREATE TABLE `capteurs_intrusion` (
  `id_capteur` int(11) NOT NULL,
  `emplacement` varchar(255) NOT NULL,
  `niveau_alerte` tinyint(1) NOT NULL DEFAULT 0,
  `date_signalement` date NOT NULL DEFAULT curdate()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_uca1400_ai_ci;

--
-- Dumping data for table `capteurs_intrusion`
--

INSERT INTO `capteurs_intrusion` (`id_capteur`, `emplacement`, `niveau_alerte`, `date_signalement`) VALUES
(1, 'Salle des serveurs', 0, '2024-12-18'),
(2, 'Entrée secondaire', 0, '2024-12-17'),
(3, 'Couloir technique', 0, '2024-12-16');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alertes_locales`
--
ALTER TABLE `alertes_locales`
  ADD PRIMARY KEY (`id_alerte`),
  ADD KEY `fk_alertes_locales_cameras` (`id_camera`);

--
-- Indexes for table `caméras`
--
ALTER TABLE `caméras`
  ADD PRIMARY KEY (`id_camera`);

--
-- Indexes for table `capteurs_intrusion`
--
ALTER TABLE `capteurs_intrusion`
  ADD PRIMARY KEY (`id_capteur`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alertes_locales`
--
ALTER TABLE `alertes_locales`
  MODIFY `id_alerte` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `caméras`
--
ALTER TABLE `caméras`
  MODIFY `id_camera` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `capteurs_intrusion`
--
ALTER TABLE `capteurs_intrusion`
  MODIFY `id_capteur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alertes_locales`
--
ALTER TABLE `alertes_locales`
  ADD CONSTRAINT `fk_alertes_locales_cameras` FOREIGN KEY (`id_camera`) REFERENCES `caméras` (`id_camera`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
