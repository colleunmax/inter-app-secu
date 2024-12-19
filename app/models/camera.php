<?php
require_once __DIR__ . '/../../config.php';

class Camera {
    private $pdo;

    public function __construct() {
        $this->pdo = getSecurityConnection();
    }

    public function getAll() {
        $stmt = $this->pdo->query("
            SELECT id_camera, emplacement, statut, date_maj 
            FROM caméras
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }    

    public function add($emplacement, $statut) {
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,20}$/u', $emplacement)) {
            throw new Exception("L'emplacement doit contenir uniquement des lettres (max 20 caractères).");
        }
        $stmt = $this->pdo->prepare("
            INSERT INTO caméras (emplacement, statut, date_maj)
            VALUES (:emplacement, :statut, NOW())
        ");
        $stmt->execute([
            'emplacement' => $emplacement,
            'statut' => $statut
        ]);
    }

    public function delete($id) {
        $stmt = $this->pdo->prepare("
            DELETE FROM caméras WHERE id_camera = :id
        ");
        $stmt->execute(['id' => $id]);
    }
}
?>
