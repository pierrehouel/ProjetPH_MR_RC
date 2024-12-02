<?php
session_start();
include 'database.php'; ?>

<!DOCTYPE html>
<html>
<head>
    <title>Création de compte</title>
    <link rel="stylesheet" href="css/Style.css">
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body>
    <form action="traitement_creation_compte.php" method="POST">
        <h1>Création de compte</h1>
        <div class="inputs">
            <label for="firstname">Prénom :</label>
            <input type="text" name="firstname" id="firstname" placeholder="Prénom" required>

            <label for="lastname">Nom :</label>
            <input type="text" name="lastname" id="lastname" placeholder="Nom" required>

            <label for="login">Identifiant (login) :</label>
            <input type="text" name="login" id="login" placeholder="Identifiant" required>

            <label for="email">Email :</label>
            <input type="email" name="email" id="email" placeholder="Email" required>

            <label for="password">Mot de passe :</label>
            <input type="password" name="password" id="password" placeholder="Mot de passe" required>

            <label for="confirm_password">Confirmer le mot de passe :</label>
            <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirmer le mot de passe" required>
        </div>
        
        <!-- Affichage des messages d'erreur et de succès ici -->
        <div>
            <?php if (isset($_SESSION['error_message'])): ?>
                <div class="error-message">
                    <?php 
                    echo $_SESSION['error_message']; 
                    unset($_SESSION['error_message']); // Supprime le message après l'avoir affiché
                    ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['success_message'])): ?>
                <div class="success-message">
                    <?php 
                    echo $_SESSION['success_message']; 
                    unset($_SESSION['success_message']); // Supprime le message après l'avoir affiché
                    ?>
                </div>
            <?php endif; ?>
        </div>

        <div>
            <button type="submit">Créer un compte</button>
        </div>
    </form>
</body>
</html>