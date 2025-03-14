<?php
/**
 * camera.php
 *
 * Ce fichier contient la logique pour gérer les caméras dans le système.
 * Les fonctions incluent l'ajout, la modification, la suppression et la récupération des données des caméras.
 * Les caméras sont liées aux alertes et peuvent être sélectionnées pour créer des alertes locales.
 * Utilise la base de données pour stocker les informations des caméras.
 */

require_once 'core/database.php';

class Camera {
    private $securityPDO;

    public function __construct() {
        $this->securityPDO = Database::getSecurityPDO();
    }

    public function getAll() {
        $stmt = $this->securityPDO->query("SELECT * FROM cameras");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public function add($emplacement, $statut) {
        if (!preg_match('/^[a-zA-ZÀ-ÿ\s]{1,20}$/u', $emplacement)) {
            throw new Exception("L'emplacement doit contenir uniquement des lettres (max 20 caractères).");
        }
        $stmt = $this->securityPDO->prepare("
            INSERT INTO cameras (emplacement, statut, date_maj)
            VALUES (:emplacement, :statut, NOW())
        ");
        $stmt->execute([
            'emplacement' => $emplacement,
            'statut' => $statut
        ]);
    }

    public function delete($id) {
        $stmt = $this->securityPDO->prepare("
            DELETE FROM cameras WHERE id_camera = :id
        ");
        $stmt->execute(['id' => $id]);
    }
}
?>
