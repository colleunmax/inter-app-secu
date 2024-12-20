-- Database: `smartcity_db`
USE smartcity_db;


-- Insertion des données dans la table `Type_Capteur`
INSERT INTO `Type_Capteur` (`type`)
VALUES 
('consomation_eau'),
('fuite'),
('intrusion'),
('intrusion'),
('energie'),
('parking'),
('test');

-- Insertion des données dans la table `Capteurs`
INSERT INTO `Capteurs` (`nom_capteur`, `departement`, `statut`, `type_capteur`)
VALUES 
('Default Sensor :p', 'aucun', 1, 7);

-- Insertion des données dans la table `Alertes_Globales`
INSERT INTO `Alertes_Globales` (`description`, `niveau`, `statut`, `id_capteur`)
VALUES 
('Lorem Ipsum dolor sit amet', 1, 1, 1);

-- Insertion des données dans la table `Rapports_Globales`
INSERT INTO `Rapports_Globales` (`departement`, `periode`, `description`, `date_creation`)
VALUES 
('aucun', 7, 'Intruder alert! (jveuxmourir)', '2024-12-18');