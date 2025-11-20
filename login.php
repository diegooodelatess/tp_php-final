<?php
session_start();
require "sql.php";

$errors = [];

// Vérification champs
if (empty($_POST['username']) || empty($_POST['password'])) {
    $errors[] = "Veuillez remplir tous les champs";
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: formulaire.php");
    exit;
}

// Vérification utilisateur
$stmt = $dbh->prepare("SELECT * FROM id_user WHERE user_name = ?");
$stmt->execute([ $_POST['username'] ]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION['errors'] = ["Utilisateur introuvable"];
    header("Location: formulaire.php");
    exit;
}

// Vérification mot de passe
if (!password_verify($_POST['password'], $user['password'])) {
    $_SESSION['errors'] = ["Mot de passe incorrect"];
    header("Location: formulaire.php");
    exit;
}

// Connexion réussie
session_regenerate_id(true);
$_SESSION['username'] = $user['user_name'];

header("Location: message.php");
exit;
