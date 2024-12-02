<?php
session_start();
include 'db/database.php';?>

<!DOCTYPE html>
<html>
<head>
    <title>Connexion</title>
    <link rel="stylesheet" href="css/Style.css">
</head>
<body>
    <form action="traitement_connexion.php" method="POST">
        <h1>Se connecter</h1>
        <p class="choose-email"><?php echo "Veuillez utiliser votre email et mot de passe :"; ?></p>
        <div class="inputs">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
        </div>
        <p class="inscription">
            <?php echo "Je n'ai pas de"; ?> 
            <a href="Inscription.php">Compte</a>, je m'en crée un.
        </p>
        <div>
            <button type="submit">Se connecter</button>
        </div>
        <?php
            if (isset($_SESSION['error_message'])) {
                echo '<p class="error-message error-below">' . htmlspecialchars($_SESSION['error_message']) . '</p>';
                unset($_SESSION['error_message']);
            }
        ?>
    </form>
</body>
</html>
