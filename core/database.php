<?php
/**
 * Class Database
 * 
 * Utilitaire de connexion à différentes bases de données.
 */
class Database {
    
    /**
     * Crée une connexion PDO à une base de données spécifique.
     *
     * @param string $host L'adresse du serveur de base de données.
     * @param string $dbName Le nom de la base de données.
     * @param string $user Le nom d'utilisateur pour la connexion.
     * @param string $passwd Le mot de passe pour la connexion.
     * 
     * @return PDO L'instance PDO pour la connexion à la base de données.
     */
    protected static function connect(string $host, string $dbName, string $user, string $passwd) {
        $pdo = new PDO(
            "mysql:host=".$host.";dbname=".$dbName,
            $user,
            $passwd,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
        $pdo->exec("SET NAMES utf8;");
        return $pdo;
    }

    /**
     * Retourne une instance PDO connectée à la base de données sécurisée.
     *
     * @return PDO L'instance PDO pour la connexion à la base de données sécurisée.
     */
    public static function getSecurityPDO(): PDO {
        $host = $_ENV["LOCAL_HOST"];
        $dbName = $_ENV["LOCAL_DBNAME"];
        $user = $_ENV["LOCAL_USERNAME"];
        $password = $_ENV["LOCAL_PASSWORD"];
        return self::connect($host, $dbName, $user, $password);
    }

    /**
     * Retourne une instance PDO connectée à la base de données slave de Smartcity.
     *
     * @return PDO L'instance PDO pour la connexion à la base de données slave de Smartcity.
     */
    public static function getSlaveSmartcityPDO(): PDO {
        $host = $_ENV["SLAVE_HOST"];
        $dbName = $_ENV["SLAVE_DBNAME"];
        $user = $_ENV["SLAVE_USERNAME"];
        $password = $_ENV["SLAVE_PASSWORD"];
        return self::connect($host, $dbName, $user, $password);
    }

    /**
     * Retourne une instance PDO connectée à la base de données master de Smartcity.
     *
     * @return PDO L'instance PDO pour la connexion à la base de données master de Smartcity.
     */
    public static function getMasterSmartcityPDO(): PDO {
        $host = $_ENV["MASTER_HOST"];
        $dbName = $_ENV["MASTER_DBNAME"];
        $user = $_ENV["MASTER_USERNAME"];
        $password = $_ENV["MASTER_PASSWORD"];
        return self::connect($host, $dbName, $user, $password);
    }
}