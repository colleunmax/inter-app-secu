<?php
/**
 * Fichier de configuration global pour l'application.
 * Contient les paramètres de connexion à la base de données.
 */

define('DB_HOST', 'localhost');
define('DB_NAME', 'db_local_3');
define('DB_USER', 'root');
define('DB_PASS', '');

function getPDOConnection() {
    try {
        return new PDO('mysql:host=localhost;dbname=db_local_3', 'root', '', [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
    } catch (PDOException $e) {
        die("Erreur de connexion : " . $e->getMessage());
    }
}
?>
