<?php
/**
 * index.php
 *
 * Ce fichier est le point d'entrée principal de l'application.
 * Il initialise le routeur et traite toutes les requêtes entrantes.
 * Il charge les configurations et initialise les contrôleurs nécessaires.
 * C'est la première étape pour toute navigation ou interaction utilisateur.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
require_once '../core/router.php';
require_once '../core/database.php';
require_once '../config.php';

$router = new Router();
$router->run();
$controllerFile = dirname(__DIR__) . "/app/controllers/HomeController.php";
?>