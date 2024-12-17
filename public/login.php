<?php
/**
 * Script Login pour vérifier les identifiants et ouvrir une session utilisateur.
 */

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') { // Correction ici
    $username = $_POST['username'];
    $password = $_POST['password'];

    if ($username === 'admin' && $password === '123') {
        $_SESSION['logged_in'] = true;
        header('Location: dashboard.php'); // Redirection correcte
        exit();
    } else {
        echo "Identifiants incorrects. Veuillez réessayer.";
    }
}
?>
