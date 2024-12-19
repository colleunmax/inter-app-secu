<?php
/**
 * HomeController.php
 *
 * Ce fichier contient le contrôleur principal pour la page d'accueil de l'application.
 * Il gère l'affichage de la page d'accueil et la logique principale pour les utilisateurs.
 * Les données affichées sont souvent extraites de la base de données pour montrer des informations dynamiques.
 * C'est le point d'entrée pour les utilisateurs après leur connexion.
 */

require_once '../app/models/camera.php';
require_once '../app/models/sensor.php';

class HomeController {
    public function index() {
        $cameraModel = new Camera();
        $sensorModel = new Sensor();

        $cameraId = isset($_POST['camera_id']) ? (int)$_POST['camera_id'] : 1;

        $cameras = $cameraModel->getAll();
        $selectedCamera = $cameras[$cameraId - 1] ?? $cameras[0];
        $sensors = $sensorModel->getAll();

        require_once dirname(__DIR__) . '../../home.php';
    }
}
?>