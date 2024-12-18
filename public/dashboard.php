<?php
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
    
    session_start();
    if (!isset($_SESSION['logged_in'])) {
        header('Location: login.php');
        exit();
    }

    require_once '../app/models/camera.php';
    require_once '../app/models/sensor.php';

    $cameraModel = new Camera();
    $sensorModel = new Sensor();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_POST['add_camera'])) {
            $emplacement = $_POST['camera_emplacement'];
            if (!empty($emplacement)) {
                $cameraModel->add($emplacement);
            }
        }

        if (isset($_POST['delete_camera'])) {
            $id = (int)$_POST['camera_id'];
            $cameraModel->delete($id);
        }

        if (isset($_POST['add_sensor'])) {
            $nom = $_POST['sensor_nom'];
            $type = $_POST['sensor_type'];
            $departement = $_POST['sensor_departement'];
            if (!empty($nom) && !empty($type) && !empty($departement)) {
                $sensorModel->add($nom, $type, $departement);
            }
        }

        if (isset($_POST['delete_sensor'])) {
            $id = (int)$_POST['sensor_id'];
            $sensorModel->delete($id);
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
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

    <?php require_once '../app/views/templates/header.php'; ?>

    <h1>Dashboard - Gestion des Caméras et Capteurs</h1>

    <h2>Caméras</h2>

    <form method="POST" action="">
        <label for="camera_id">Choisir une caméra :</label>
        <select name="camera_id" id="camera_id">
            <?php foreach ($cameras as $camera): ?>
                <option value="<?= $camera['id_camera']; ?>" 
                    <?= isset($selectedCamera) && $selectedCamera['id_camera'] == $camera['id_camera'] ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($camera['emplacement']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" name="camera_select">Changer</button>
    </form>

    <?php if ($selectedCamera): ?>
        <h3>Caméra sélectionnée : <?= htmlspecialchars($selectedCamera['emplacement']); ?></h3>
        <video src="assets/video_1.mp4" controls width="500"></video>
    <?php else: ?>
        <p>Aucune caméra sélectionnée.</p>
    <?php endif; ?>

    <h3>Ajouter une caméra</h3>
    <form method="POST">
        <input type="text" name="camera_emplacement" placeholder="Emplacement de la caméra" required>
        <button type="submit" name="add_camera">Ajouter</button>
    </form>

    <h3>Liste des caméras</h3>
    <ul>
        <?php foreach ($cameras as $camera): ?>
            <li>
                <?= htmlspecialchars($camera['emplacement']); ?> - Statut : <?= $camera['statut'] ? 'Actif' : 'Inactif'; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="camera_id" value="<?= $camera['id_camera']; ?>">
                    <button type="submit" name="delete_camera">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <h2>Capteurs</h2>

    <h3>Ajouter un capteur</h3>
    <form method="POST">
        <input type="text" name="sensor_nom" placeholder="Nom du capteur" required>
        <input type="number" name="sensor_type" placeholder="Type (ID)" required>
        <input type="text" name="sensor_departement" placeholder="Département" required>
        <button type="submit" name="add_sensor">Ajouter</button>
    </form>

    <h3>Liste des capteurs</h3>
    <ul>
        <?php foreach ($sensors as $sensor): ?>
            <li>
                <?= htmlspecialchars($sensor['nom_capteur']); ?> 
                (<?= htmlspecialchars($sensor['emplacement'] ?? 'N/A'); ?>) -
                Statut : <?= $sensor['statut'] ? 'Actif' : 'Inactif'; ?> -
                Niveau alerte : <?= isset($sensor['niveau_alerte']) && $sensor['niveau_alerte'] ? 'On' : 'Off'; ?>
                <form method="POST" style="display:inline;">
                    <input type="hidden" name="sensor_id" value="<?= $sensor['id_capteur']; ?>">
                    <button type="submit" name="delete_sensor">Supprimer</button>
                </form>
            </li>
        <?php endforeach; ?>
    </ul>

    <form method="POST" action="logout.php">
        <button type="submit">Se déconnecter</button>
    </form>

    <?php require_once '../app/views/templates/footer.php'; ?>
</body>
</html>
