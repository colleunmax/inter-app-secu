<?php
/**
 * database.php
 *
 * Ce fichier contient les connexions à la base de données.
 * Deux connexions principales sont configurées : une pour la sécurité et une pour le système smartcity.
 * Les fonctions incluent la gestion des exceptions liées à la base de données.
 * C'est un fichier central pour toutes les opérations de base de données.
 */

require_once '../config.php';

class Database {
    public static function connect() {
        try {
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $pdo;
        } catch (PDOException $e) {
            die("Erreur : " . $e->getMessage());
        }
    }
}
?>