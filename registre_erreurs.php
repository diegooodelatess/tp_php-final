<?php
session_start();
require "sql.php";

$errors = [];

// Vérification des champs
if (empty($_POST['username'])) $errors[] = "Nom d'utilisateur vide";
if (empty($_POST['password'])) $errors[] = "Mot de passe vide";

// Vérifier si username existe
if (empty($errors)) {
    $stmt = $dbh->prepare("SELECT * FROM id_user WHERE user_name = ?");
    $stmt->execute([ $_POST['username'] ]);
    
    if ($stmt->fetch()) {
        $errors[] = "Nom d'utilisateur déjà pris";
    }
}

// Gestion des erreurs
if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header("Location: formulaire.php");
    exit;
}

// Vérification sécurité mot de passe
if (strlen($_POST['password']) < 8) {
    $_SESSION['errors'] = ["Le mot de passe doit contenir au moins 8 caractères"];
    header("Location: formulaire.php");
    exit;
}

// Hash du mot de passe
$hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Insertion dans la table id_user
$stmt = $dbh->prepare("INSERT INTO id_user (user_name, password) VALUES (?, ?)");
$stmt->execute([
    $_POST['username'],
    $hash
]);

$_SESSION['success'] = "Inscription réussie ! Maintenant connecte-toi.";
header("Location: formulaire.php");
exit;
