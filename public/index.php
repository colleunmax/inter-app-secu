<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<?php
session_start();
require_once '../core/router.php';
require_once '../core/database.php';
require_once '../config.php';

$router = new Router();
$router->run();
$controllerFile = dirname(__DIR__) . "/app/controllers/HomeController.php";
?>
