SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- Database: `smartcity_db`
CREATE DATABASE IF NOT EXISTS smartcity_db;
USE smartcity_db;

-- Table structure for table `type_capteur`
CREATE TABLE IF NOT EXISTS `Type_Capteur` (
  `id_type_capteur` INT PRIMARY KEY AUTO_INCREMENT,
  `type` VARCHAR(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `capteurs`
CREATE TABLE IF NOT EXISTS `Capteurs` (
  `id_capteur` INT PRIMARY KEY AUTO_INCREMENT,
  `nom_capteur` VARCHAR(128) NOT NULL,
  `departement` VARCHAR(10) NOT NULL,
  `statut` BOOLEAN NOT NULL,
  `date_derniere_maj` DATE DEFAULT CURRENT_DATE NOT NULL,
  `type_capteur` INT NOT NULL,
  CONSTRAINT `fk_capteurs_type_capteur`
    FOREIGN KEY (`type_capteur`) REFERENCES `Type_Capteur`(`id_type_capteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `alertes_globales`
CREATE TABLE IF NOT EXISTS `Alertes_Globales` (
  `id_alerte` INT PRIMARY KEY AUTO_INCREMENT,
  `description` VARCHAR(255) NOT NULL,
  `niveau` INT NOT NULL,
  `date_creation` DATE DEFAULT CURRENT_DATE NOT NULL,
  `statut` BOOLEAN NOT NULL,
  `id_capteur` INT NOT NULL,
  CONSTRAINT `fk_alertes_globales_capteur`
    FOREIGN KEY (`id_capteur`) REFERENCES `Capteurs`(`id_capteur`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Table structure for table `rapports_globales`
CREATE TABLE IF NOT EXISTS `Rapports_Globales` (
  `id_rapport` INT PRIMARY KEY AUTO_INCREMENT,
  `departement` VARCHAR(10) NOT NULL,
  `periode` INT NOT NULL,
  `description` VARCHAR(255) NOT NULL,
  `date_creation` DATE DEFAULT CURRENT_DATE NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;