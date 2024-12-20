<?php
/**
 * config.php
 *
 * Ce fichier contient les configurations principales de l'application.
 * Les paramètres incluent les connexions à la base de données et d'autres constantes globales.
 * Il est inclus dans presque tous les fichiers pour assurer une configuration centralisée.
 */

use Dotenv\Dotenv;

require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

define('DB_SMARTCITY_HOST', 'localhost');
define('DB_SMARTCITY_NAME', 'smartcity_db');
define('DB_SMARTCITY_USER', 'root');
define('DB_SMARTCITY_PASS', '');

function getSecurityConnection() {
    try {
        $host = $_ENV["LOCAL_HOST"];
        $dbName = $_ENV["LOCAL_DBNAME"];
        $user = $_ENV["LOCAL_USERNAME"];
        $password = $_ENV["LOCAL_PASSWORD"];
        return new PDO(
            "mysql:host=".$host.";dbname=".$dbName,
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données Locale : " . $e->getMessage());
    }
}

function getSmartcityConnection() {
    try {
        $host = $_ENV["MASTER_HOST"];
        $dbName = $_ENV["MASTER_DBNAME"];
        $user = $_ENV["MASTER_USERNAME"];
        $password = $_ENV["MASTER_PASSWORD"];
        return new PDO(
            "mysql:host=".$host.";dbname=".$dbName,
            $user,
            $password,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    } catch (PDOException $e) {
        die("Erreur de connexion à la base de données distante : " . $e->getMessage());
    }
}
?>
