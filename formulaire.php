<?php session_start(); ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion / Inscription</title>
</head>
<body>

<h1>Connexion</h1>
<form method="post" action="login.php">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
    <input type="password" name="password" placeholder="Mot de passe" required><br>
    <button type="submit">Se connecter</button>
</form>

<h1>Inscription</h1>
<form method="post" action="registre_erreurs.php">
    <input type="text" name="username" placeholder="Nom d'utilisateur" required><br>
    <input type="password" name="password" placeholder="Mot de passe" required><br>
    <input type="text" name="name" placeholder="Nom complet" required><br>
    <button type="submit">S'inscrire</button>
</form>

<?php
// Affichage des erreurs
if (!empty($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $err) {
        echo "<p style='color:red;'>".htmlspecialchars($err)."</p>";
    }
    unset($_SESSION['errors']);
}

// Affichage des succès
if (!empty($_SESSION['success'])) {
    echo "<p style='color:green;'>".htmlspecialchars($_SESSION['success'])."</p>";
    unset($_SESSION['success']);
}
?>
</body>
</html>
