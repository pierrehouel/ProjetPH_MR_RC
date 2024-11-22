<?php include 'database.php';?>

<!DOCTYPE html>
<html>
<head>
    <title><?php echo "Accueil"; ?></title>
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
    <link rel="stylesheet" href="page.css">
</head>
<body>
    <ul class="navbar">
        <li class="active"><a href="Page.php"><?php echo "Retour"; ?></a></li>
    </ul>

    <h1><?php echo "Images sélectionnées"; ?></h1>
    <div id="imageContainer">
        <?php
        // Récupération des images depuis une source simulée (par exemple, localStorage en JS)
        $selectedImages = isset($_SESSION['selectedImages']) ? $_SESSION['selectedImages'] : [];

        if (!empty($selectedImages)) {
            foreach ($selectedImages as $src) {
                echo '<img src="' . htmlspecialchars($src) . '" alt="Selected Image" width="150">';
            }
        } else {
            echo '<p>Aucune image sélectionnée.</p>';
        }
        ?>
    </div>

    <!-- Si tu veux inclure des scripts pour gérer des actions dynamiques -->
    <script>
        // Exemple : JS pour charger des données si nécessaire
        console.log('Scripts JS peuvent être ajoutés ici si besoin.');
    </script>
</body>
</html>
