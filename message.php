<?php
session_start();
require "sql.php";

// Vérifie si l'utilisateur est connecté
if (!isset($_SESSION['username'])) {
    header("Location: formulaire.php");
    exit;
}

// Envoi du message
if (isset($_POST['message']) && !empty(trim($_POST['message']))) {
    $message = trim($_POST['message']);
    if (strlen($message) <= 500) {
        $stmt = $dbh->prepare("INSERT INTO message (message, name) VALUES (?, ?)");
        $stmt->execute([$message, $_SESSION['username']]);
    } else {
        $_SESSION['errors'] = ["Le message est trop long (max 500 caractères)"];
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
</head>
<body>
<h1>Messages</h1>

<?php
// Affichage des messages
$req = $dbh->query("SELECT * FROM message ORDER BY id DESC");
foreach ($req as $msg) {
    echo "<p><strong>" . htmlspecialchars($msg['name']) . "</strong><br>";
    echo nl2br(htmlspecialchars($msg['message'])) . "</p><hr>";
}

// Affichage des erreurs
if (!empty($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $err) {
        echo "<p style='color:red;'>".htmlspecialchars($err)."</p>";
    }
    unset($_SESSION['errors']);
}
?>

<form method="post">
    <textarea name="message" placeholder="Écris ton message ici" required></textarea><br>
    <button type="submit">Envoyer</button>
</form>

<a href="logout.php">Se déconnecter</a>
</body>
</html>