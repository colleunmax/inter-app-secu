-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Dec 17, 2024 at 01:02 AM
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
-- Database: `db_local_3`
--

-- --------------------------------------------------------

--
-- Table structure for table `alert`
--

CREATE TABLE `alert` (
  `id_alert` int NOT NULL,
  `date` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `id_sensor` int DEFAULT NULL,
  `id_type` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

-- --------------------------------------------------------

--
-- Table structure for table `camera`
--

CREATE TABLE `camera` (
  `id_camera` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `ip` varchar(15) NOT NULL,
  `port` int NOT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `camera`
--

INSERT INTO `camera` (`id_camera`, `name`, `ip`, `port`, `description`) VALUES
(1, 'Caméra Hall', '192.168.1.10', 8080, 'Caméra principale dans le hall'),
(2, 'Caméra Parking', '192.168.1.11', 8081, 'Caméra extérieure pour le parking'),
(3, 'Caméra Salle de contrôle', '192.168.1.12', 8082, 'Caméra dans la salle de contrôle');

-- --------------------------------------------------------

--
-- Table structure for table `sensor`
--

CREATE TABLE `sensor` (
  `id_sensor` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `state` tinyint(1) NOT NULL DEFAULT '0',
  `id_type` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sensor`
--

INSERT INTO `sensor` (`id_sensor`, `name`, `description`, `state`, `id_type`) VALUES
(1, 'Capteur Hall Principal', 'Détecte les mouvements dans le hall', 0, 1),
(2, 'Capteur Porte Entrée', 'Surveille la porte principale', 0, 2),
(3, 'Capteur Présence Bureau', 'Présence dans la salle de contrôle', 0, 3);

-- --------------------------------------------------------

--
-- Table structure for table `sensor_type`
--

CREATE TABLE `sensor_type` (
  `id_type` int NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `sensor_type`
--

INSERT INTO `sensor_type` (`id_type`, `name`) VALUES
(1, 'Capteur de mouvement'),
(2, 'Capteur de porte'),
(3, 'Capteur de présence');

-- --------------------------------------------------------

--
-- Table structure for table `type_alert`
--

CREATE TABLE `type_alert` (
  `id_type` int NOT NULL,
  `name` varchar(255) NOT NULL,
  `priority` int NOT NULL,
  `message` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Dumping data for table `type_alert`
--

INSERT INTO `type_alert` (`id_type`, `name`, `priority`, `message`) VALUES
(1, 'Intrusion détectée', 3, 'Vérifiez immédiatement les caméras'),
(2, 'Mouvement suspect', 2, 'Surveillez la zone concernée'),
(3, 'Capteur défectueux', 1, 'Vérifiez le capteur et réinitialisez');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `alert`
--
ALTER TABLE `alert`
  ADD PRIMARY KEY (`id_alert`),
  ADD KEY `id_sensor` (`id_sensor`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `camera`
--
ALTER TABLE `camera`
  ADD PRIMARY KEY (`id_camera`);

--
-- Indexes for table `sensor`
--
ALTER TABLE `sensor`
  ADD PRIMARY KEY (`id_sensor`),
  ADD KEY `id_type` (`id_type`);

--
-- Indexes for table `sensor_type`
--
ALTER TABLE `sensor_type`
  ADD PRIMARY KEY (`id_type`);

--
-- Indexes for table `type_alert`
--
ALTER TABLE `type_alert`
  ADD PRIMARY KEY (`id_type`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alert`
--
ALTER TABLE `alert`
  MODIFY `id_alert` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `camera`
--
ALTER TABLE `camera`
  MODIFY `id_camera` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sensor`
--
ALTER TABLE `sensor`
  MODIFY `id_sensor` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `sensor_type`
--
ALTER TABLE `sensor_type`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `type_alert`
--
ALTER TABLE `type_alert`
  MODIFY `id_type` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `alert`
--
ALTER TABLE `alert`
  ADD CONSTRAINT `alert_ibfk_1` FOREIGN KEY (`id_sensor`) REFERENCES `sensor` (`id_sensor`),
  ADD CONSTRAINT `alert_ibfk_2` FOREIGN KEY (`id_type`) REFERENCES `type_alert` (`id_type`);

--
-- Constraints for table `sensor`
--
ALTER TABLE `sensor`
  ADD CONSTRAINT `sensor_ibfk_1` FOREIGN KEY (`id_type`) REFERENCES `sensor_type` (`id_type`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
