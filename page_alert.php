<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
// Vérification si l'utilisateur est connecté
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/config.php';
require_once 'app/models/alert.php';

// Connexions aux bases de données
$pdo_security = getSecurityConnection();
$pdo_smartcity = getSmartcityConnection();

// Modèle des alertes
$alertModel = new Alert($pdo_security);

// Gestion des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['resolve_alert'])) {
            $alertId = (int)$_POST['alert_id'];
            $alertModel->resolveLocalAlert($alertId); // Appeler la méthode pour résoudre l'alerte
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$globalAlerts = $alertModel->getGlobalAlerts();
$localAlerts = $alertModel->getLocalAlerts();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alertes</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        h1, h2 { text-align: left; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/app/views/templates/header.php'; ?>

    <h1>Page d'Alertes</h1>

    <form method="GET" action="index.php" style="margin-top: 20px;">
        <input type="hidden" name="controller" value="dashboard">
        <input type="hidden" name="action" value="index">
        <button type="submit">Retour au Dashboard</button>
    </form>


    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <h2>Alertes Globales</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Description</th>
            <th>Niveau</th>
            <th>Date de Création</th>
            <th>Statut</th>
            <th>ID Capteur</th>
        </tr>
        <?php foreach ($globalAlerts as $alert): ?>
        <tr>
            <td><?= $alert['id_alerte'] ?></td>
            <td><?= htmlspecialchars($alert['description']) ?></td>
            <td><?= $alert['niveau'] ?></td>
            <td><?= $alert['date_creation'] ?></td>
            <td><?= $alert['statut'] ? 'Actif' : 'Résolu' ?></td>
            <td><?= $alert['id_capteur'] ?></td>
        </tr>
        <?php endforeach; ?>
    </table>

    <h2>Alertes Locales</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>ID Caméra</th>
            <th>Description</th>
            <th>Date de Signalement</th>
            <th>Statut</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($localAlerts as $alert): ?>
        <tr>
            <td><?= $alert['id_alerte'] ?></td>
            <td><?= $alert['id_camera'] ?></td>
            <td><?= htmlspecialchars($alert['description']) ?></td>
            <td><?= $alert['date_signalement'] ?></td>
            <td><?= $alert['statut'] ? 'Actif' : 'Résolu' ?></td>
            <td>
                <?php if ($alert['statut']): ?>
                    <!-- Bouton pour marquer l'alerte comme résolue -->
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="alert_id" value="<?= $alert['id_alerte'] ?>">
                        <button type="submit" name="resolve_alert">Marquer comme résolu</button>
                    </form>
                <?php else: ?>
                    <span>Alerte résolue</span>
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <br>

    <form method="POST" action="logout.php">
        <button type="submit">Se déconnecter</button>
    </form>


    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
