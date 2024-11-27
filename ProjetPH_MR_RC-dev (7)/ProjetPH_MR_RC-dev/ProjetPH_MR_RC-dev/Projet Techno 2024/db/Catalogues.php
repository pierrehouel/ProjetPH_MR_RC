<?php include 'database.php';?>

<!DOCTYPE html>
<html>
    <head>
        <title><?php echo "catalogue"?></title>
        <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
        <link rel="stylesheet" href="page.css">
        <link rel="stylesheet" href="index.php">
        <link rel="stylesheet" href="connexion.php">
    </head>
    <body>
        <div class="content">
        
            <ul class="navbar">
                <li class="active"><a href="index.php"><?php echo "Retour"?></a></li>
            </ul>
            <h1><?php echo "Catalogue"?></h1>      
            
            <div class="creation-catalogue">
                <input type="text" id="catalogue-name" placeholder="Nom du catalogue">
                <input type="file" id="image-input" multiple>
                <button id="creation-catalogue"><?php echo "Créer le Catalogue"?></button>

            </div>
            
        </div>

        
    </body>

    <script type="text/php" src="index.php"></script>



</html>
