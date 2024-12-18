-- Database: `smartcity_db`
USE smartcity_db;


-- Insertion des données dans la table `Type_Capteur`
INSERT INTO `Type_Capteur` (`type`)
VALUES 
('Capteur de température'),
('Capteur de mouvement'),
('Capteur de qualité de l’air');

-- Insertion des données dans la table `Capteurs`
INSERT INTO `Capteurs` (`nom_capteur`, `departement`, `statut`, `date_derniere_maj`, `type_capteur`)
VALUES 
('TempSensor01', '75', 1, '2024-12-18', 1),
('MotionSensor02', '92', 0, '2024-12-17', 2),
('AirQualitySensor03', '93', 1, '2024-12-16', 3);

-- Insertion des données dans la table `Alertes_Globales`
INSERT INTO `Alertes_Globales` (`description`, `niveau`, `date_creation`, `statut`, `id_capteur`)
VALUES 
('Température élevée détectée', 3, '2024-12-18', 1, 1),
('Mouvement suspect détecté', 2, '2024-12-17', 0, 2),
('Pollution de l’air anormale', 4, '2024-12-16', 1, 3);

-- Insertion des données dans la table `Rapports_Globales`
INSERT INTO `Rapports_Globales` (`departement`, `periode`, `description`, `date_creation`)
VALUES 
('75', 202412, 'Rapport mensuel sur les températures', '2024-12-18'),
('92', 202412, 'Rapport sur les mouvements détectés', '2024-12-17'),
('93', 202412, 'Rapport sur la qualité de l’air', '2024-12-16');