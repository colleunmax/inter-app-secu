<?php
/**
 * logout.php
 *
 * Ce fichier gère la déconnexion des utilisateurs.
 * Il détruit les sessions actives et redirige vers la page de connexion.
 * C'est un processus simple pour sécuriser les comptes utilisateurs.
 */

session_start();
if (isset($_SESSION['logged_in'])) {
    session_destroy();
}
header('Location: index.php');
exit();
?>