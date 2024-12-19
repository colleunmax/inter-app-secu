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
require_once __DIR__ . '/app/models/alert.php';

$alertModel = new Alert();
$globalAlerts = $alertModel->getGlobalAlerts();
$localAlerts = $alertModel->getLocalAlerts();
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

    <!-- Tableau des alertes globales -->
    <h2>Alertes Globales</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>Description</th>
                <th>Niveau</th>
                <th>Date de Création</th>
                <th>Statut</th>
                <th>ID Capteur</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($globalAlerts as $alert): ?>
                <tr>
                    <td><?= htmlspecialchars($alert['id_alerte']) ?></td>
                    <td><?= htmlspecialchars($alert['description']) ?></td>
                    <td><?= htmlspecialchars($alert['niveau']) ?></td>
                    <td><?= htmlspecialchars($alert['date_creation']) ?></td>
                    <td><?= htmlspecialchars($alert['statut']) ?></td>
                    <td><?= htmlspecialchars($alert['id_capteur']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Tableau des alertes locales -->
    <h2>Alertes Locales</h2>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>ID</th>
                <th>ID Caméra</th>
                <th>Description</th>
                <th>Date de Signalement</th>
                <th>Statut</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($localAlerts as $alert): ?>
                <tr>
                    <td><?= htmlspecialchars($alert['id_alerte']) ?></td>
                    <td><?= htmlspecialchars($alert['id_camera']) ?></td>
                    <td><?= htmlspecialchars($alert['description']) ?></td>
                    <td><?= htmlspecialchars($alert['date_signalement']) ?></td>
                    <td><?= htmlspecialchars($alert['statut']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Bouton vers le Dashboard -->
    <form method="GET" action="index.php" style="margin-top: 20px;">
        <input type="hidden" name="controller" value="dashboard">
        <input type="hidden" name="action" value="index">
        <button type="submit">Retour au Dashboard</button>
    </form>

    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
