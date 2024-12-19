<?php
/**
 * Classe HomeController qui affiche la page principale et gère les caméras et capteurs.
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