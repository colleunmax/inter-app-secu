<?php
/**
 * page_alert.php
 *
 * Ce fichier affiche la liste des alertes globales et locales dans l'interface utilisateur.
 * Il fournit des fonctionnalités pour visualiser, résoudre ou supprimer les alertes.
 * Les données sont extraites de la base de données via les modèles.
 * Ce fichier est une partie essentielle du tableau de bord.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once __DIR__ . '/config.php';
require_once 'app/models/alert.php';

$pdo_security = getSecurityConnection();
$alertModel = new Alert($pdo_security);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['resolve_alert'])) {
            $alertId = (int)$_POST['alert_id'];
            $alertModel->resolveLocalAlert($alertId);
            $success = "Alerte résolue avec succès.";
        }

        if (isset($_POST['delete_alert'])) {
            $alertId = (int)$_POST['alert_id'];
            $alertModel->deleteLocalAlert($alertId);
            $success = "Alerte supprimée avec succès.";
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
    <link rel="stylesheet" href="/public/styles.css">
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        h1, h2 { text-align: left; }
    </style>
    <link rel="stylesheet" href="assets/css/style.css?v=1.1">
</head>
<body>

    <?php require_once __DIR__ . '/app/views/templates/header.php'; ?>
    
    <main>
        
        <section class="information-section">
            <h2>Tableau de bord  des Camera & Capteurs</h2>
            <p>Ici, vous pouvez voir les différentes alertes des capteurs comme des cameras mises en place dans notre infrastructure. <span>
            (Si vous voyez cette page et que vous n’êtes pas un personnel âcre dite par la SS, prévenir un administrateur et de quitter cette page dans les plus brefs délais sous risque de poursuite judiciaire.)
            </span></p>
            <?php if (isset($success)): ?>
                <p style="color:green;"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
            <?php if (isset($error)): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
        </section>

    </main>

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
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="alert_id" value="<?= $alert['id_alerte'] ?>">
                        <button type="submit" name="resolve_alert">Résoudre</button>
                    </form>
                <?php endif; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="alert_id" value="<?= $alert['id_alerte'] ?>">
                    <button type="submit" name="delete_alert">Supprimer</button>
                </form>
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
