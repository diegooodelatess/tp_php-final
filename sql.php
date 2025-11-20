<?php
// Connexion à la base MySQL avec PDO
$dsn = "mysql:host=localhost;dbname=user;charset=utf8";
$user = "root";
$pass = "Doris10101010!"; // À remplacer par une variable d'environnement pour plus de sécurité

try {
    $dbh = new PDO($dsn, $user, $pass);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur DB : " . $e->getMessage());
}
?>
