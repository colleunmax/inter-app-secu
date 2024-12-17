<?php
/**
 * Routeur principal pour rediriger les requêtes utilisateur vers les contrôleurs appropriés.
 */

class Router {
    public function run() {
        $controller = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['controller'] ?? 'home');
        $action = preg_replace('/[^a-zA-Z0-9_]/', '', $_GET['action'] ?? 'index');
        $controllerFile = dirname(__DIR__) . "/app/controllers/" . ucfirst($controller) . "Controller.php";

        if (file_exists($controllerFile)) {
            require_once $controllerFile;
            $className = ucfirst($controller) . "Controller";
            $obj = new $className();
            if (method_exists($obj, $action)) {
                $obj->$action();
            } else {
                $this->showError(404, "Action non trouvée.");
            }
        } else {
            $this->showError(404, "Page non trouvée.");
        }
    }

    private function showError($code, $message) {
        http_response_code($code);
        echo $message;
        exit();
    }
}
?>
