<?php
/**
 * login.php
 *
 * Ce fichier gère le processus de connexion des utilisateurs.
 * Il vérifie les identifiants dans la base de données et crée des sessions utilisateur.
 * En cas d'échec, il affiche des messages d'erreur.
 * Il redirige les utilisateurs vers le tableau de bord après une connexion réussie.
 */

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function secure_input($input, &$error_message) {
    $input = substr($input, 0, 20); 
    $input = htmlspecialchars($input, ENT_QUOTES, 'UTF-8');

    if (preg_match('/^[a-zA-Z0-9]+$/', $input)) {
        return $input; 
    } else {
        $error_message = "Seules les lettres et les chiffres sont autorisés (20 caractères max).";
        return ''; 
    }
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username_error = '';
    $password_error = '';

    $username = secure_input($_POST['username'] ?? '', $username_error);
    $password = secure_input($_POST['password'] ?? '', $password_error);

    session_start();

    if ($username_error || $password_error) {
        $error_message = $username_error ?: $password_error; 
        header('Location: index.php?controller=home&action=login&error=' . urlencode($error_message));
        exit();
    }

    if ($username === 'admin' && $password === 'QSDF') {
        $_SESSION['logged_in'] = true;
        header('Location: index.php?controller=dashboard&action=index');
        exit();
    } else {
        $error_message = "Identifiants incorrects. Veuillez réessayer.";
        header('Location: index.php?controller=home&action=login&error=' . urlencode($error_message));
        exit();
    }
}

?>