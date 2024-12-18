<?php
require_once __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();


function getPDO(string $host, string $dbName, string $user, string $password) {
    try {
        $options = [PDO::ATTR_ERRMODE => pdo::ERRMODE_EXCEPTION];
        return new PDO('mysql:host='.$host.';dbname='.$dbName, $user, $password, $options);
    } catch (PDOException $e) {
        die("Erreur de connexion a la DB.".$e->getMessage()());
    }
}


function getLocalPDO() {
    return getPDO($_ENV["LOCAL_HOST"], $_ENV["LOCAL_DBNAME"], $_ENV["LOCAL_USERNAME"], $_ENV["LOCAL_PASSWORD"]);
}


function getGlobalPDO() {
    return getPDO($_ENV["GLOBAL_HOST"], $_ENV["GLOBAL_DBNAME"], $_ENV["GLOBAL_USERNAME"], $_ENV["GLOBAL_PASSWORD"]);
}
?>
