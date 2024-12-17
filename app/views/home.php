<?php require 'templates/header.php'; ?>

<h1>Page Principale</h1>

<h2>Caméras</h2>

<!-- Formulaire pour sélectionner une caméra -->
<form method="POST" action="">
    <label for="camera_id">Choisir une caméra :</label>
    <select name="camera_id" id="camera_id">
        <option value="1" <?= isset($_POST['camera_id']) && $_POST['camera_id'] == 1 ? 'selected' : '' ?>>Caméra 1</option>
        <option value="2" <?= isset($_POST['camera_id']) && $_POST['camera_id'] == 2 ? 'selected' : '' ?>>Caméra 2</option>
        <option value="3" <?= isset($_POST['camera_id']) && $_POST['camera_id'] == 3 ? 'selected' : '' ?>>Caméra 3</option>
    </select>
    <button type="submit">Changer</button>
</form>

<!-- Afficher la caméra sélectionnée -->
<h3><?= htmlspecialchars($selectedCamera['name']); ?></h3>
<video src="assets/video_1.mp4" controls></video>

<h2>Capteurs</h2>
<?php if (!empty($sensors)): ?>
    <?php foreach ($sensors as $sensor): ?>
        <p>
            <?= htmlspecialchars($sensor['name']); ?> - État : 
            <?= $sensor['state'] ? 'Activé' : 'Désactivé'; ?>
        </p>
    <?php endforeach; ?>
<?php else: ?>
    <p>Aucun capteur disponible.</p>
<?php endif; ?>



<h2>Connexion</h2>
<form method="POST" action="login.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Se connecter</button>
</form>

<?php require 'templates/footer.php'; ?>