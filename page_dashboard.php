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
require_once 'app/models/alert.php';

// Connexions aux bases de données
$pdo_security = getSecurityConnection();
$pdo_smartcity = getSmartcityConnection();

// Modèles
$cameraModel = new Camera($pdo_security);
$sensorModel = new Sensor($pdo_security, $pdo_smartcity);
$alertModel = new Alert($pdo_security);

// Gestion des formulaires
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // Ajouter une caméra
        if (isset($_POST['add_camera'])) {
            $cameraModel->add($_POST['camera_emplacement'], $_POST['camera_statut']);
        }

        // Supprimer une caméra
        if (isset($_POST['delete_camera'])) {
            $cameraModel->delete((int)$_POST['camera_id']);
        }

        // Ajouter un capteur
        if (isset($_POST['add_sensor'])) {
            $sensorModel->add($_POST['sensor_nom']);
        }

        // Supprimer un capteur
        if (isset($_POST['delete_sensor'])) {
            $sensorModel->delete((int)$_POST['sensor_id']);
        }

        // Créer une alerte locale
        if (isset($_POST['create_local_alert'])) {
            $description = $_POST['alert_description'] ?? '';
            $cameraId = $_POST['camera_id'] ?? null;

            if (empty($description)) {
                throw new Exception("La description de l'alerte est obligatoire.");
            }

            if (empty($cameraId)) {
                throw new Exception("Une caméra doit être sélectionnée pour créer une alerte.");
            }

            $alertModel->createLocalAlert($cameraId, $description);
            $success = "Alerte locale créée avec succès.";
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

// Vidéos aléatoires pour les caméras
$videoFiles = [
    'assets/video_1.mp4',
    'assets/video_2.mp4',
    'assets/video_3.mp4',
];
$randomVideo = $videoFiles[array_rand($videoFiles)];
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
    <?php if (isset($success)): ?>
        <p style="color:green;"><?= htmlspecialchars($success) ?></p>
    <?php endif; ?>

    <!-- Bouton vers la page des alertes -->
    <form method="GET" action="index.php" style="margin-top: 20px;">
        <input type="hidden" name="controller" value="alert">
        <input type="hidden" name="action" value="index">
        <button type="submit">Aller à la page des alertes</button>
    </form>
    
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
    <?php 
        $videoPath = 'assets/video_' . $selectedCamera['id_video'] . '.mp4';
        if (!file_exists($videoPath)) {
            $videoPath = 'assets/default.mp4'; // Vidéo par défaut si la vidéo n'existe pas
        }
    ?>
    <video src="<?= htmlspecialchars($videoPath) ?>" controls width="500"></video>

    <!-- Formulaire pour créer une alerte locale -->
    <h3>Créer une alerte locale</h3>
    <form method="POST">
        <input type="hidden" name="camera_id" value="<?= $selectedCamera['id_camera'] ?>">
        <label for="alert_description">Description :</label>
        <input type="text" name="alert_description" id="alert_description" maxlength="255" required>
        <button type="submit" name="create_local_alert">Créer une alerte locale</button>
    </form>
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

    <br>
    
    <form method="POST" action="logout.php">
        <button type="submit">Se déconnecter</button>
    </form>

    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
