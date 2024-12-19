<?php
/**
 * config.php
 *
 * Ce fichier contient les configurations principales de l'application.
 * Les paramètres incluent les connexions à la base de données et d'autres constantes globales.
 * Il est inclus dans presque tous les fichiers pour assurer une configuration centralisée.
 */

define('DB_SECURITY_HOST', 'localhost');
define('DB_SECURITY_NAME', 'security_db');
define('DB_SECURITY_USER', 'root');
define('DB_SECURITY_PASS', '');

define('DB_SMARTCITY_HOST', 'localhost');
define('DB_SMARTCITY_NAME', 'smartcity_db');
define('DB_SMARTCITY_USER', 'root');
define('DB_SMARTCITY_PASS', '');

function getSecurityConnection() {
    try {
        return new PDO(
            "mysql:host=" . DB_SECURITY_HOST . ";dbname=" . DB_SECURITY_NAME,
            DB_SECURITY_USER,
            DB_SECURITY_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion à security_db : " . $e->getMessage());
    }
}

function getSmartcityConnection() {
    try {
        return new PDO(
            "mysql:host=" . DB_SMARTCITY_HOST . ";dbname=" . DB_SMARTCITY_NAME,
            DB_SMARTCITY_USER,
            DB_SMARTCITY_PASS,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion à smartcity_db : " . $e->getMessage());
    }
}
?>
