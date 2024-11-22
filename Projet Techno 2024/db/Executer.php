<?php include 'database.php';?>

<!DOCTYPE html>
<html lang="en">
<head>

    <link rel="stylesheet" href="page.css">
    <link rel="stylesheet" href="index.php">
    <link rel="stylesheet" href="index.js">
    <link rel="stylesheet" href="connexion.php">
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo "Sélection de zone et envoi d'image";?></title>
   
</head>
<body>
    <ul class="navbar">
        <li class="active"><a href="Page.php"><?php echo "Retour"?></a></li>
    </ul>

    <div id="container">
        <form id="uploadForm" action="process.php" method="post" enctype="multipart/form-data">
            <input type="file" name="image" id="imageUpload" class="input-file" required>
            <br>
            <img id="image" alt="Image à analyser" width="200">
            <canvas id="canvas" width="300" height="150"></canvas>
            <button type="submit" class="btn" id="submitSelection"><?php echo "Soumettre la sélection"?></button>
            <input type="hidden" name="points" id="pointsData">
        </form>
    </div>

</body>
</html>
