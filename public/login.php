<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function secure_input($input) {
    return htmlspecialchars(substr($input, 0, 30), ENT_QUOTES, 'UTF-8');
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = secure_input($_POST['username'] ?? '');
    $password = secure_input($_POST['password'] ?? '');
    session_start();

    if ($username === 'admin' && $password === 'test') {
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
