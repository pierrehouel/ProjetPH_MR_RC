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
        <li class="active"><a href="#"><?php echo "Sommet"; ?></a></li>
        <li class="left"><a href="Catalogues.php"><?php echo "Catalogue"; ?></a></li>
        <li class="right"><a href="images.php"><?php echo "Images"; ?></a></li>
        <li class="right"><a href="#"><?php echo "Édition"; ?></a></li>
        <li class="right"><a href="Executer.php"><?php echo "Exécuter"; ?></a></li>
        <li class="right"><a href="Connexion.php"><?php echo "Connexion"; ?></a></li>
    </ul>

    <div class="content">
        <h1><?php echo "Objectif"; ?></h1>

        <div class="box">
            <img class="avatar" src="../images/everest.png" alt="<?php echo "everest"; ?>" />
        </div>
        
        <p>
            <?php echo "Bienvenue sur Sommet, une plateforme interactive innovante conçue pour faciliter l'exploration et l'analyse d'images. Que vous soyez curieux, étudiant ou professionnel, notre site vous permet d'obtenir des informations détaillées en toute simplicité.

            Grâce à un système intuitif de points de sélection, vous pouvez cliquer sur une zone spécifique de l'image qui vous intéresse. Chaque point vous dévoile des données précises et enrichissantes liées à cette partie de l'image. Que ce soit pour comprendre une œuvre d'art, analyser des détails techniques ou explorer des éléments visuels complexes, Sommet est l'outil idéal pour satisfaire votre curiosité.
            
            Plongez dans une expérience interactive qui allie simplicité d'utilisation et richesse d'informations, et laissez-vous guider par Sommet pour découvrir des perspectives inédites sur vos images."; ?>
        </p>
    </div>
</body>
</html>
