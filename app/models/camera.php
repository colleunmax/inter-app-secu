<?php
/**
 * Classe Camera qui gère les caméras avec des méthodes CRUD en base de données.
 */

require_once '../config.php';

class Camera {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDOConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM camera");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($name, $ip, $port) {
        $stmt = $this->pdo->prepare("INSERT INTO camera (name, ip, port) VALUES (:name, :ip, :port)");
        $stmt->execute(['name' => $name, 'ip' => $ip, 'port' => $port]);
    }    

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM camera WHERE id_camera = :id");
        $stmt->execute(['id' => $id]);
    }
}   
?>
