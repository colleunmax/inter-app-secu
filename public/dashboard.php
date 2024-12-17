<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
?>


<?php
/**
 * Vue Dashboard pour gérer les caméras et capteurs en CRUD.
 */



require_once '../app/models/Camera.php';
require_once '../app/models/Sensor.php';

$cameraModel = new Camera();
$sensorModel = new Sensor();

// Ajouter une caméra
if (isset($_POST['add_camera'])) {
    $name = $_POST['name'];
    $ip = $_POST['ip'];
    $port = $_POST['port'];

    if (!empty($name) && !empty($ip) && !empty($port)) {
        $cameraModel->add($name, $ip, $port);
    } else {
        echo "Veuillez remplir tous les champs.";
    }
}

// Supprimer une caméra
if (isset($_POST['delete_camera'])) {
    if (isset($_POST['camera_id']) && is_numeric($_POST['camera_id'])) {
        $id = (int)$_POST['camera_id'];
        $cameraModel->delete($id);
    } else {
        echo "Erreur : ID caméra manquant ou invalide.";
    }
}


// Ajouter un capteur
if (isset($_POST['add_sensor'])) {
    $name = $_POST['sensor_name'];
    if (!empty($name)) {
        $sensorModel->add($name);
    }
}

// Supprimer un capteur
if (isset($_POST['delete_sensor'])) {
    $id = (int)$_POST['sensor_id'];
    $sensorModel->delete($id);
}

$cameras = $cameraModel->getAll();
$sensors = $sensorModel->getAll();
?>

<h1>Mode Admin - Gestion des Caméras et Capteurs</h1>

<h2>Ajouter une caméra</h2>
<form method="POST">
    <input type="text" name="name" placeholder="Nom de la caméra" required>
    <input type="text" name="ip" placeholder="IP de la caméra" required>
    <input type="text" name="port" placeholder="Port de la caméra" required>
    <button type="submit" name="add_camera">Ajouter</button>
</form>


<h2>Liste des caméras</h2>
<ul>
<?php foreach ($cameras as $index => $camera): ?>
    <li>
        <?= htmlspecialchars($camera['name']); ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="camera_id" value="<?= $camera['id_camera']; ?>">
            <button type="submit" name="delete_camera">Supprimer</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<h2>Ajouter un capteur</h2>
<form method="POST">
    <input type="text" name="sensor_name" placeholder="Nom du capteur" required>
    <button type="submit" name="add_sensor">Ajouter</button>
</form>

<h2>Liste des capteurs</h2>
<ul>
<?php foreach ($sensors as $index => $sensor): ?>
    <li>
        <?= htmlspecialchars($sensor['name']); ?>
        <form method="POST" style="display:inline;">
            <input type="hidden" name="sensor_id" value="<?= $sensor['id_sensor']; ?>">
            <button type="submit" name="delete_sensor">Supprimer</button>
        </form>
    </li>
<?php endforeach; ?>
</ul>

<form method="POST" action="logout.php">
    <button type="submit">Se déconnecter</button>
</form>
