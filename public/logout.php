<?php
/**
 * Script Logout pour détruire la session et déconnecter l'utilisateur.
 */

session_start();
if (isset($_SESSION['logged_in'])) {
    session_destroy();
}
header('Location: index.php');
exit();
?>
