<?php
session_start();
include 'database.php';

// Activer l'affichage des erreurs pour le débogage
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Vérifier si le formulaire de connexion a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données de connexion
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Requête pour vérifier si l'utilisateur existe dans la base de données
    $stmt = $pdo->prepare("SELECT * FROM UserAccount WHERE email = ?");
    $stmt->bindParam(1, $email, PDO::PARAM_STR);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérification si l'utilisateur existe
    if ($user) {
        // Vérification du mot de passe
        if (password_verify($password, $user['password'])) {
            // Connexion réussie
            $_SESSION['user'] = $user;
            $_SESSION['userRoleId'] = $user['userRoleId']; 
            header("Location: index.php"); // Rediriger vers la page principale
            exit;
        } else {
          
            header("Location: connexion.php"); // Rediriger à nouveau vers la page de connexion
            $_SESSION['error_message'] = "Mot de passe incorrect.";
            exit;
        }
    } else {
       
        header("Location: connexion.php"); // Rediriger à nouveau vers la page de connexion
        $_SESSION['error_message'] = "Aucun utilisateur trouvé avec cet email.";
        exit;
    }
}
?>