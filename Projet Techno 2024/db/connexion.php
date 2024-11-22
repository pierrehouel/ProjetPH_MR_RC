<?php
$host = 'mysql-projetwebl2.alwaysdata.net'; // adresse du serveur SQL
$dbname = 'projettechno_2024'; // nom de la base de données
$username = '386539_gabriel'; // nom d'utilisateur SQL
$password = 'Projet2024'; // mot de passe SQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie !";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>