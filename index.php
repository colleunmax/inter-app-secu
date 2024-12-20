<?php
/**
* index.php
*
* Ce fichier représente une page de connexion pour l'application.
* Il vérifie la présence d'un message d'erreur et l'affiche si nécessaire.
* Une session sécurisée est utilisée pour générer un jeton CSRF et éviter les attaques CSRF.
* Un formulaire HTML est présenté pour permettre à l'utilisateur d'entrer ses identifiants 
* (nom d'utilisateur et mot de passe).
* Les champs du formulaire sont protégés avec des attributs HTML `maxlength` et `required`.
* Le formulaire envoie les données en méthode POST au fichier `login.php` pour authentification.
*/

$error_message = isset($_GET['error']) ? htmlspecialchars($_GET['error']) : '';

if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/public/styles.css">
    <title>SS | Connexion</title>
</head>
<body>
    <?php require_once __DIR__ . '/app/views/templates/header.php'; ?>

    <section id="login-section">
        <h2>
            <img src="public/assets/logo-login.svg" alt="In a pixelised font: Smartcity Security">
        </h2>
        <form id="login-form" method="POST" action="public/login.php">
            <h3>Connexion Admin</h3>
            <div>
                <label for="username">Login<span>*</span></label>
                <input placeholder="user1" type="text" name="username" id="username" maxlength="30" required>
            </div>

            <div>
                <label for="password">Mot de passe<span>*</span></label>
                <input placeholder="yourpassword" type="password" name="password" id="password" maxlength="30" required>
            </div>

            <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']; ?>">
            <?php if (!empty($error_message)): ?>
                <p><?= $error_message; ?></p>
            <?php endif; ?>
            <button type="submit">Se connecter</button>
        </form>
    </section>

    <?php require_once __DIR__ . '/app/views/templates/footer.php'; ?>
</body>
</html>
