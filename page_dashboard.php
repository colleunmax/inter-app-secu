<?php  
/**
 * page_dashboard.php
 *
 * Ce fichier représente le tableau de bord principal de l'application de gestion.
 * Il offre un aperçu général des caméras, des capteurs et des alertes enregistrées dans le système.
 * Les données affichées dans cette page sont récupérées dynamiquement via les modèles 
 * correspondants (`camera`, `sensor`, `alert`) en se connectant aux bases de données.
 * Les utilisateurs peuvent interagir avec les éléments affichés pour effectuer des actions 
 * comme activer/désactiver des capteurs, créer ou supprimer des alertes, et modifier les informations des caméras.
 * Le tableau de bord est conçu pour offrir une vue centralisée et faciliter la gestion des équipements 
 * et des événements en cours.
 * Ce fichier inclut également des mécanismes pour gérer les formulaires et les messages d'erreur ou de succès.
 * La sécurité est renforcée grâce à des sessions, vérifiant que seul un utilisateur authentifié peut accéder à cette page.
 * Enfin, ce fichier agit comme un point clé de l'interface utilisateur interactive, 
 * permettant une gestion intuitive des ressources du système.
 */

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}

require_once 'app/models/capteur.php';
require_once 'app/models/camera.php';
require_once 'app/models/alert.php';
require_once 'core/database.php';

$pdo_security = Database::getSecurityPDO();
$pdo_smartcity = Database::getSlaveSmartcityPDO();

$cameraModel = new Camera($pdo_security);
$sensorModel = new Capteur($pdo_security, $pdo_smartcity);
$alertModel = new Alert($pdo_security, $pdo_smartcity);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        if (isset($_POST['add_camera'])) {
            $cameraModel->add($_POST['camera_emplacement'], $_POST['camera_statut']);
        }

        if (isset($_POST['delete_camera'])) {
            try {
                $cameraId = (int)$_POST['camera_id'];
                
                $cameraModel->delete($cameraId);
                
                $success = "Caméra supprimée avec succès.";
            } catch (PDOException $pdoException) {
                if ($pdoException->getCode() === '23000') {
                    $error = "Impossible de supprimer la caméra car elle est liée à une ou plusieurs alertes.";
                } else {
                    $error = "Une erreur est survenue lors de la suppression de la caméra.";
                }
            } catch (Exception $deleteError) {
                $error = $deleteError->getMessage();
            }
        }

        if (isset($_POST['add_sensor'])) {
            $sensorModel->add($_POST['sensor_nom']);
        }

        if (isset($_POST['delete_sensor'])) {
            $sensorModel->delete((int)$_POST['sensor_id']);
        }

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

        if (isset($_POST['toggle'])) {
            $sensorId = (int)$_POST['sensor_id'];
            $newLevel = $_POST['current_level'] == 1 ? 0 : 1; 
            $sensorModel->updateAlertLevel($sensorId, $newLevel);
            $success = $newLevel == 1 ? "Capteur activé avec succès." : "Capteur désactivé avec succès.";
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
    <title>SS | Dashboard</title>
    <link rel="stylesheet" href="/public/styles.css">
    <link rel="stylesheet" href="assets/css/style.css?v=1.1">
</head>
<body>

    <?php require_once __DIR__ . '/app/views/templates/header.php'; ?>

    <main>

        <section class="information-section">
            <h2>Tableau de bord  des Camera & Capteurs</h2>
            <p>Ici, vous pouvez voir les différents capteurs de sécurité de la Smartcity Security Corporation. <span>
            (Si vous voyez cette page et que vous n’êtes pas un personnel âcre dite par la SS, prévenir un administrateur et de quitter cette page dans les plus brefs délais sous risque de poursuite judiciaire.)
            </span></p>
            <?php if (isset($error)): ?>
                <p style="color:red;"><?= htmlspecialchars($error) ?></p>
            <?php endif; ?>
            <?php if (isset($success)): ?>
                <p style="color:green;"><?= htmlspecialchars($success) ?></p>
            <?php endif; ?>
        </section>

        <section id="camera-section">
            <h2>Caméras : <?= htmlspecialchars($selectedCamera['emplacement']) ?></h2>
            <form method="POST">
                <label for="camera_id">Choix de la source</label>
                <div>
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
                </div>
            </form>

            <?php if ($selectedCamera): ?>
                <?php 
                    $videoPath = 'assets/video_' . $selectedCamera['id_video'] . '.mp4';
                    if (!file_exists($videoPath)) {
                        $videoPath = 'assets/default.mp4';
                    }
                ?>
                <video src="<?= htmlspecialchars($videoPath) ?>" controls width="500"></video>
                <form method="POST">
                    <input type="hidden" name="camera_id" value="<?= $selectedCamera['id_camera'] ?>">
                    <label for="alert_description">Description :</label>
                    <input type="text" name="alert_description" id="alert_description" maxlength="255" required>
                    <button type="submit" name="create_local_alert">Signaler!</button>
                </form>
            <?php endif; ?>
        </section>

        <section id="add-camera-section">
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
                            <button type="submit" name="delete_camera">Del</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </section>


        <section id="sensor-section">
            <h2>Capteurs</h2>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Emplacement</th>
                    <th>État d'alerte</th>
                    <th>Actions</th>
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
                    <td>    
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="sensor_id" value="<?= $sensor['id_capteur'] ?>">
                            <input type="hidden" name="current_level" value="<?= $sensor['niveau_alerte'] ?>">
                            <button type="submit" name="toggle">
                                <?= $sensor['niveau_alerte'] == 1 ? 'Désactiver' : 'Activer' ?>
                            </button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
            <h3>Ajouter un capteur</h3>
            <form method="POST">
                <input type="text" name="sensor_nom" placeholder="Nom du capteur" required>
                <button type="submit" name="add_sensor">Ajouter</button>
            </form>
        </section>

    </main>

    <br>
    
    <form method="POST" action="logout.php">
        <button type="submit">Se déconnecter</button>
    </form>

    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
