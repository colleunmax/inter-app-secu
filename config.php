<?php
/**
 * Fichier de configuration global pour l'application.
 * Contient les paramètres de connexion à la base de données.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'security_db'); 
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDOConnection() {
    try {
        return new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
