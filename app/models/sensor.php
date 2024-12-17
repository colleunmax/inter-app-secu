<?php
/**
 * Classe Sensor qui gère les capteurs avec des méthodes CRUD en base de données.
 */

require_once '../config.php';

class Sensor {
    private $pdo;

    public function __construct() {
        $this->pdo = getPDOConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("SELECT * FROM sensor");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($name) {
        $stmt = $this->pdo->prepare("INSERT INTO sensor (name, state) VALUES (:name, 0)");
        $stmt->execute(['name' => $name]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("DELETE FROM sensor WHERE id_sensor = :id");
        $stmt->execute(['id' => $id]);
    }
}
?>
