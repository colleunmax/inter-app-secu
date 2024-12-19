<?php
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
            FROM alertes_globales
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
}
?>
