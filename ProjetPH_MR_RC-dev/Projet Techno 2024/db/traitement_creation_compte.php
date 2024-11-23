<?php 
session_start();
include 'database.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si la connexion à la base de données est valide
if (!$pdo) {
    $_SESSION['error_message'] = "Erreur de connexion à la base de données.";
    header("Location: Compte.php");
    exit;
}

// Récupération des données du formulaire
$firstname = $_POST['firstname'];
$lastname = $_POST['lastname'];
$login = $_POST['login'];
$email = $_POST['email'];
$password = $_POST['password'];
$confirm_password = $_POST['confirm_password'];

// Vérification des mots de passe
if ($password !== $confirm_password) {
    $_SESSION['error_message'] = "Les mots de passe ne correspondent pas.";
    header("Location: Compte.php");
    exit;
}

// Vérification de l'unicité de l'email et du login
$stmt_check = $pdo->prepare("SELECT * FROM UserAccount WHERE login = ? OR email = ?");
$stmt_check->bindParam(1, $login, PDO::PARAM_STR);
$stmt_check->bindParam(2, $email, PDO::PARAM_STR);
$stmt_check->execute();
$result = $stmt_check->fetchAll(PDO::FETCH_ASSOC);

if (count($result) > 0) {
    $_SESSION['error_message'] = "L'identifiant ou l'email existe déjà.";
    header("Location: Compte.php");
    exit;
}
$stmt_check->closeCursor();

// Hachage du mot de passe
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Définir le rôle par défaut (par ex., non-editor)
$userRoleId = 2; // Supposons que "non-editor" correspond à l'ID 2 dans la table UserRole

// Insertion des données dans la table UserAccount
$stmt = $pdo->prepare("INSERT INTO UserAccount (userRoleId, login, firstname, lastname, email, password) VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bindParam(1, $userRoleId, PDO::PARAM_INT);
$stmt->bindParam(2, $login, PDO::PARAM_STR);
$stmt->bindParam(3, $firstname, PDO::PARAM_STR);
$stmt->bindParam(4, $lastname, PDO::PARAM_STR);
$stmt->bindParam(5, $email, PDO::PARAM_STR);
$stmt->bindParam(6, $hashed_password, PDO::PARAM_STR);

if ($stmt->execute()) {
    $_SESSION['success_message'] = "Compte créé avec succès ! Connectez-vous.";
    header("Location: connexion.php");
} else {
    $_SESSION['error_message'] = "Erreur lors de la création du compte.";
    header("Location: Compte.php");
}

$stmt->closeCursor();
$pdo = null; // Ferme la connexion PDO
?>