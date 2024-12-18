-- Database: `security_db`
USE security_db;

-- Insertion des données dans la table `Caméras`
INSERT INTO `Caméras` (`emplacement`, `statut`, `date_maj`)
VALUES 
('Entrée principale', 1, '2024-12-18'),
('Parking sous-terrain', 0, '2024-12-17'),
('Couloir des bureaux', 1, '2024-12-16');

-- Insertion des données dans la table `Alertes_Locales`
INSERT INTO `Alertes_Locales` (`id_camera`, `description`, `date_signalement`, `statut`)
VALUES 
(1, 'Mouvement détecté à l’entrée', '2024-12-18', 1),
(2, 'Intrusion dans le parking', '2024-12-17', 0),
(3, 'Activité suspecte près des bureaux', '2024-12-16', 1);

-- Insertion des données dans la table `Capteurs_Intrusion`
INSERT INTO `Capteurs_Intrusion` (`emplacement`, `niveau_alerte`, `date_signalement`)
VALUES 
('Salle des serveurs', 1, '2024-12-18'),
('Entrée secondaire', 0, '2024-12-17'),
('Couloir technique', 1, '2024-12-16');
