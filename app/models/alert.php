<?php
/**
 * alert.php
 *
 * Ce fichier gère les alertes locales et globales.
 * Il inclut des classes et des fonctions pour créer, afficher, mettre à jour et supprimer des alertes.
 * Les alertes sont liées aux capteurs ou aux caméras dans le système.
 * Ce fichier utilise la connexion à la base de données pour enregistrer les alertes.
 */

require_once __DIR__ . '/../../config.php';

class Alert {
    private $pdo_smartcity;
    private $pdo_security;

    public function __construct() {
        $this->pdo_smartcity = getSmartcityConnection();
        $this->pdo_security = getSecurityConnection();
    }

    public function getGlobalAlerts() {
        $stmt = $this->pdo_smartcity->query("
            SELECT id_alerte, description, niveau, date_creation, statut, id_capteur 
            FROM Alertes_Globales
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getLocalAlerts() {
        $stmt = $this->pdo_security->query("
            SELECT id_alerte, id_camera, description, date_signalement, statut 
            FROM alertes_locales
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function createLocalAlert($cameraId, $description) {
        $stmt = $this->pdo_security->prepare("
            INSERT INTO alertes_locales (id_camera, description, date_signalement, statut)
            VALUES (:cameraId, :description, NOW(), 1)
        ");
        $stmt->execute([
            'cameraId' => $cameraId,
            'description' => $description
        ]);
    }

    public function createGlobalAlert($sensorId, $description) {
        try {
            $stmt = $this->pdo_smartcity->prepare("
                INSERT INTO Alertes_Globales (description, niveau, date_creation, statut, id_capteur)
                VALUES (:description, 3, NOW(), 1, :id_capteur)
            ");
            $stmt->execute([
                'description' => $description,
                'id_capteur' => $sensorId
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la création de l'alerte globale : " . $e->getMessage());
        }
    }

    public function deleteLocalAlert($alertId) {
        try {
            $stmt = $this->pdo_security->prepare("DELETE FROM alertes_locales WHERE id_alerte = :id");
            $stmt->execute(['id' => $alertId]);
        } catch (PDOException $e) {
            throw new Exception("Erreur lors de la suppression de l'alerte locale : " . $e->getMessage());
        }
    }

    public function resolveLocalAlert($alertId) {
        $stmt = $this->pdo_security->prepare("
            UPDATE alertes_locales
            SET statut = 0
            WHERE id_alerte = :id
        ");
        $stmt->execute(['id' => $alertId]);
    }
}
?>
