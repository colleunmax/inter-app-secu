<?php
session_start();

$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

require_once __DIR__ . '/app/views/templates/header.php';
?>

<h2>Connexion</h2>

<?php if (!empty($error_message)): ?>
    <p style="color:red;"><?= $error_message; ?></p>
<?php endif; ?>

<form method="POST" action="login.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" maxlength="30" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" maxlength="30" required><br>

    <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">

    <button type="submit">Se connecter</button>
</form>

<?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
