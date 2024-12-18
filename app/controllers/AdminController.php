<?php
/**
 * Classe AdminController qui gère les fonctionnalités liées aux administrateurs.
 */

require_once '../app/models/camera.php';
require_once '../app/models/sensor.php';

class AdminController {
    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if ($_POST['username'] === ADMIN_USER && $_POST['password'] === ADMIN_PASS) {
                $_SESSION['admin'] = true;
                header("Location: index.php?controller=admin&action=index");
                exit();
            }
        }
        require_once '../app/views/login.php';
    }

    public function index() {
        if (!isset($_SESSION['admin'])) {
            header("Location: index.php?controller=admin&action=login");
            exit();
        }
        $camera = new Camera();
        $sensor = new Sensor();

        if (isset($_POST['add_camera'])) {
            $camera->add($_POST);
        }

        if (isset($_POST['delete_camera'])) {
            $camera->delete($_POST['id']);
        }

        require_once '../app/views/admin.php';
    }
}
?>
