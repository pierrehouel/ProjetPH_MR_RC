<?php include 'database.php'; ?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo "Création de compte"; ?></title>
        <link rel="stylesheet" href="Style.css">
    </head>
    <body>
        <form action="traitement_creation_compte.php" method="POST">
            <h1><?php echo "Création de compte"; ?></h1>
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
            <div>
                <button type="submit"><?php echo "Créer un compte"; ?></button>
            </div>
        </form>
    </body>
</html>