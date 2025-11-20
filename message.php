<?php
session_start();
require "sql.php";

if (!isset($_SESSION['username'])) {
    header("Location: formulaire.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty(trim($_POST['message']))) {
    $message = trim($_POST['message']);

    if (strlen($message) > 500) {
        $_SESSION['errors'] = ["Le message est trop long (maximum 500 caractères)."];
    } else {
        $stmt = $dbh->prepare("INSERT INTO message (message, name) VALUES (?, ?)");
        $stmt->execute([$message, $_SESSION['username']]);
    }

    header("Location: message.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Messages</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        .message { margin-bottom: 20px; }
        .author { font-weight: bold; }
        .timestamp { color: gray; font-size: 0.9em; }
        .error { color: red; }
        textarea { width: 100%; max-width: 500px; height: 100px; }
        button { margin-top: 10px; padding: 8px 16px; }
    </style>
</head>
<body>

<h1>Messages</h1>

<?php
if (!empty($_SESSION['errors'])) {
    foreach ($_SESSION['errors'] as $err) {
        echo "<p class='error'>" . htmlspecialchars($err) . "</p>";
    }
    unset($_SESSION['errors']);
}

$req = $dbh->query("SELECT * FROM message ORDER BY id DESC");
foreach ($req as $msg) {
    $date = new DateTime($msg['envoyé à']);
    $formattedDate = $date->format('d/m/Y à H:i');

    echo "<div class='message'>";
    echo "<p class='author'>" . htmlspecialchars($msg['name']) . "</p>";
    echo "<p class='timestamp'>Envoyé le " . $formattedDate . "</p>";
    echo "<p>" . nl2br(htmlspecialchars($msg['message'])) . "</p>";
    echo "</div><hr>";
}
?>

<form method="post">
    <textarea name="message" placeholder="Écris ton message ici" required></textarea><br>
    <button type="submit">Envoyer</button>
</form>

<p><a href="logout.php">Se déconnecter</a></p>

</body>
</html>