<?php
session_start();
require "sql.php";

$errors = [];

if (empty($_POST['username'])) $errors[] = "Nom d'utilisateur vide";
if (empty($_POST['password'])) $errors[] = "Mot de passe vide";
if (empty($_POST['name'])) $errors[] = "Nom complet vide";

if (empty($errors)) {
    $stmt = $dbh->prepare("SELECT * FROM id_user WHERE user_name = ?");
    $stmt->execute([ $_POST['username'] ]);
    if ($stmt->fetch()) {
        $errors[] = "Nom d'utilisateur déjà pris";
    }
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: formulaire.php");
    exit;
}

if (strlen($_POST['password']) < 8) {
    $_SESSION['errors'] = ["Le mot de passe doit contenir au moins 8 caractères"];
    header("Location: formulaire.php");
    exit;
}

$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

$stmt = $dbh->prepare("INSERT INTO id_user (user_name, password, name) VALUES (?, ?, ?)");
$stmt->execute([
    $_POST['username'],
    $hash,
    $_POST['name']
]);

$_SESSION['success'] = "Inscription réussie ! Maintenant connecte-toi.";
header("Location: formulaire.php");
exit;