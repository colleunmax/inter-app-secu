<?php require 'templates/header.php'; ?>

<h2>Connexion</h2>
<form method="POST" action="login.php">
    <label for="username">Nom d'utilisateur :</label>
    <input type="text" name="username" id="username" required><br>

    <label for="password">Mot de passe :</label>
    <input type="password" name="password" id="password" required><br>

    <button type="submit">Se connecter</button>
</form>

<?php require 'templates/footer.php'; ?>