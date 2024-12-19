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
require_once 'app/models/sensor.php';
require_once 'app/models/camera.php';

// Connexions aux bases de données
$pdo_security = getSecurityConnection();
$pdo_smartcity = getSmartcityConnection();

// Modèles
$cameraModel = new Camera($pdo_security);
$sensorModel = new Sensor($pdo_security, $pdo_smartcity);

// Gestion des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_camera'])) {
            $cameraModel->add($_POST['camera_emplacement'], $_POST['camera_statut']);
        }
        if (isset($_POST['delete_camera'])) {
            $cameraModel->delete((int)$_POST['camera_id']);
        }
        if (isset($_POST['add_sensor'])) {
            $sensorModel->add($_POST['sensor_nom']);
        }
        if (isset($_POST['delete_sensor'])) {
            $sensorModel->delete((int)$_POST['sensor_id']);
        }
    } catch (Exception $e) {
        $error = $e->getMessage();
    }
}

$cameras = $cameraModel->getAll();
$sensors = $sensorModel->getAll();

$selectedCamera = $cameras[0] ?? null;
if (isset($_POST['camera_select'])) {
    $selectedCameraId = (int)$_POST['camera_id'];
    foreach ($cameras as $camera) {
        if ($camera['id_camera'] == $selectedCameraId) {
            $selectedCamera = $camera;
            break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Gestion des Caméras et Capteurs</title>
    <style>
        table { width: 100%; border-collapse: collapse; }
        table, th, td { border: 1px solid black; }
        th, td { padding: 10px; text-align: center; }
        h1, h2 { text-align: left; }
    </style>
</head>
<body>
    <?php require_once __DIR__ . '/app/views/templates/header.php'; ?>

    <h1>Dashboard - Gestion des Caméras et Capteurs</h1>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?= htmlspecialchars($error) ?></p>
    <?php endif; ?>

    <!-- Navigation -->
    <h2>Navigation</h2>
    <a href="../alert.php">Page d'Alertes</a>
    <form method="POST" action="logout.php" style="margin-top: 10px;">
        <button type="submit">Se déconnecter</button>
    </form>

    <!-- Section Caméras -->
    <h2>Caméras</h2>
    <form method="POST">
        <label for="camera_id">Choisir une caméra (actives) :</label>
        <select name="camera_id" id="camera_id">
            <?php foreach ($cameras as $camera): ?>
                <?php if ($camera['statut'] == 1): ?>
                    <option value="<?= $camera['id_camera'] ?>"
                        <?= isset($selectedCamera) && $selectedCamera['id_camera'] == $camera['id_camera'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($camera['emplacement']) ?>
                    </option>
                <?php endif; ?>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="camera_select">Sélectionner</button>
    </form>

    <?php if ($selectedCamera): ?>
        <h3>Caméra sélectionnée : <?= htmlspecialchars($selectedCamera['emplacement']) ?></h3>
        <video src="assets/video_1.mp4" controls width="500"></video>
    <?php endif; ?>

    <h3>Ajouter une caméra</h3>
    <form method="POST">
        <input type="text" name="camera_emplacement" placeholder="Emplacement" required>
        <select name="camera_statut">
            <option value="1">Up</option>
            <option value="0">Down</option>
        </select>
        <button type="submit" name="add_camera">Ajouter</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Emplacement</th>
            <th>Statut</th>
            <th>Date MAJ</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($cameras as $camera): ?>
        <tr>
            <td><?= $camera['id_camera'] ?></td>
            <td><?= htmlspecialchars($camera['emplacement']) ?></td>
            <td><?= $camera['statut'] ? 'Actif' : 'Inactif' ?></td>
            <td><?= $camera['date_maj'] ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="camera_id" value="<?= $camera['id_camera'] ?>">
                    <button type="submit" name="delete_camera">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <!-- Section Capteurs -->
    <h2>Capteurs</h2>
    <h3>Ajouter un capteur</h3>
    <form method="POST">
        <input type="text" name="sensor_nom" placeholder="Nom du capteur" required>
        <button type="submit" name="add_sensor">Ajouter</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>Emplacement</th>
            <th>État d'alerte</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($sensors as $sensor): ?>
        <tr>
            <td><?= $sensor['id_capteur'] ?></td>
            <td><?= htmlspecialchars($sensor['emplacement']) ?></td>
            <td><?= $sensor['niveau_alerte'] ? 'Alerte active' : 'Aucune alerte' ?></td>
            <td>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="sensor_id" value="<?= $sensor['id_capteur'] ?>">
                    <button type="submit" name="delete_sensor">Supprimer</button>
                </form>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>

    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
