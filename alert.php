<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Redirige si l'utilisateur n'est pas connecté
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/app/views/templates/header.php';
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertes</title>
</head>
<body>
    <h1>Page d'Alertes</h1>
    <p>Hello World</p>

    <!-- Lien vers le Dashboard -->
    <a href="dashboard.php">Retour au Dashboard</a><br>
    
    <!-- Bouton de déconnexion -->
    <form method="POST" action="logout.php" style="margin-top: 10px;">
        <button type="submit">Se déconnecter</button>
    </form>

    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
