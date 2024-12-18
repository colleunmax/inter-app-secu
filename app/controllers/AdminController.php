<?php
/**
 * Classe AdminController qui gère les fonctionnalités liées aux administrateurs.
 */

require_once '../app/models/camera.php';
require_once '../app/models/sensor.php';

class AdminController {
    public function login() {
    
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = htmlspecialchars(substr($_POST['username'], 0, 30), ENT_QUOTES, 'UTF-8');
            $password = htmlspecialchars(substr($_POST['password'], 0, 30), ENT_QUOTES, 'UTF-8');
    
            if ($username === ADMIN_USER && $password === ADMIN_PASS) {
                $_SESSION['admin'] = true;
                header("Location: index.php?controller=admin&action=index");
                exit();
            } else {
                $error_message = "Identifiants incorrects.";
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
