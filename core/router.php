<?php
class Router {
    private $controller;
    private $action;

    public function __construct() {
        ini_set('display_errors', 1);
        ini_set('display_startup_errors', 1);
        error_reporting(E_ALL);

        $this->controller = $_GET['controller'] ?? 'home';
        $this->action = $_GET['action'] ?? 'login';
    }

    public function run() {
        switch ($this->controller) {
            case 'home':
                $this->homeRoutes();
                break;

            case 'dashboard':
                $this->dashboardRoutes();
                break;

            default:
                $this->error404();
                break;
        }
    }

    private function homeRoutes() {
        if ($this->action === 'login') {
            require_once __DIR__ . '/../home.php';
        } else {
            $this->error404();
        }
    }

    private function dashboardRoutes() {
        session_start();
        if ($this->action === 'index') {
            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                header('Location: index.php?controller=home&action=login&error=' . urlencode('Veuillez vous connecter.'));
                exit();
            }
            require_once __DIR__ . '/../dashboard.php';
        } else {
            $this->error404();
        }
    }

    private function error404() {
        echo "Erreur 404 : Page non trouvée.";
        exit();
    }
}
