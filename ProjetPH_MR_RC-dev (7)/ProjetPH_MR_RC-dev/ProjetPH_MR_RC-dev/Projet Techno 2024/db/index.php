<?php include 'database.php';



ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


//on récupère le role de l'utilisateur connecté
if (isset($_GET['role'])) {
    $securite = "akunamatata";
    $donnee_chiffree = $_GET['role'];
    $entier = openssl_decrypt(base64_decode($donnee_chiffree), 'AES-128-ECB', $securite);
    if ($entier == 1){
        $role = "editeur";
    }else if($entier == 2){
        $role = "non-editeur";
    }

    //on transmet le role de l'utilisateur pour la page index
    $securite = "akunamatata";
    $entier_chiffre = base64_encode(openssl_encrypt($entier, 'AES-128-ECB', $securite));

    $lien = "Edition.php?role=" . urlencode($entier_chiffre);

} else {
    $role = "Pas connecté !";
    $entier = 2;
    $entier_chiffre = base64_encode(openssl_encrypt($entier, 'AES-128-ECB', $securite));
    $lien = "Edition.php?role=" . urlencode($entier_chiffre);

}


$CatalogueChoisie = null;
$ImageChoisie = null;

// Gestion du formulaire pour le choix du catalogue
if (isset($_POST['validerCatalogue'])) {
    $CatalogueChoisie = $_POST['catalogue'];
}
// Gestion du formulaire pour le choix de l'image
if (isset($_POST['validerImage'])) {
    $CatalogueChoisie = $_POST['catalogue'];
    $ImageChoisie = $_POST['image'];
}
?>

    <!-- appel du fichier javascript -->
    <script type="text/javascript" src="../js/edition.js"></script>
    <link rel="stylesheet" href="edit.php">
    <link rel="stylesheet" href="edit2.php">
</body>
</html>




<!DOCTYPE html>
<html>
<head>
    <title><?php echo "Accueil"; ?></title>
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
    <link rel="stylesheet" href="page.css">
</head>

<body>
    <ul class="navbar">
        <li class="active"><a href="#">Sommet</a></li>
        <li class="left"><a href="Catalogues.php">Catalogue</a></li>
        <li class="right"><a href="images.php">Images</a></li>
        <li class="right"><a href="<?php echo $lien; ?>">Édition</a></li>
        <li class="right"><a href="Executer.php">Exécuter</a></li>
        <li class="right"><a href="Connexion.php">Connexion</a></li>
    </ul>

    <div class="content">
        <p>l'utilisateur est: <?php echo $role;?></p>
        <h1><?php echo "Objectif"; ?></h1>

        <div class="box">
            <img class="avatar" src="../images/everest.png" alt="everest" />
        </div>
        
        <p>
            Bienvenue sur Sommet, une plateforme interactive innovante conçue pour faciliter l'exploration et l'analyse d'images. Que vous soyez curieux, étudiant ou professionnel, notre site vous permet d'obtenir des informations détaillées en toute simplicité.

            Grâce à un système intuitif de points de sélection, vous pouvez cliquer sur une zone spécifique de l'image qui vous intéresse. Chaque point vous dévoile des données précises et enrichissantes liées à cette partie de l'image. Que ce soit pour comprendre une œuvre d'art, analyser des détails techniques ou explorer des éléments visuels complexes, Sommet est l'outil idéal pour satisfaire votre curiosité.
            
            Plongez dans une expérience interactive qui allie simplicité d'utilisation et richesse d'informations, et laissez-vous guider par Sommet pour découvrir des perspectives inédites sur vos images.
        </p>
    </div>
</body>
</html>


    <!-- appel du fichier javascript -->
    <script type="text/javascript" src="../js/edition.js"></script>
    <link rel="stylesheet" href="edit.php">
    <link rel="stylesheet" href="edit2.php">
    <!DOCTYPE html>
<html lang="fr">
<head>

    <link rel="stylesheet" href="page2.css">
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edition</title>
   
</head>
<body>
    
    <p>l'utilisateur est: <?php echo $role;?></p>
    
    <!-- // liste déroulante pour les catégorie -->
        <?php
            $query = "SELECT name FROM Catalog";
            $stmt = $pdo->query($query);
            $catalog = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <form method='post'>
            <label for='catalogue'>Choisissez un catalogue :</label>
            <select name='catalogue' id='catalogue'>
                <option value='defaut'>--Sélectionnez un catalogue--</option>
                <?php
                    foreach ($catalog as $catalog){
                        echo '<option value="' . htmlspecialchars($catalog['name']) . '">' . htmlspecialchars($catalog['name']) . '</option>';
                    }
                ?>
            </select>
            <button type='submit' name="validerCatalogue">Valider</button>
        </form>

        <?php   
            // Si une catégorie est sélectionnée -->
            
            if (isset($_POST['validerCatalogue']) && $_POST['catalogue'] != "defaut") {

                // Requête pour récupérer les images liées au catalogue choisie
                $query = "  SELECT Image.name AS imgName FROM Image
                            INNER JOIN CatalogImage ON Image.id = CatalogImage.imageId
                            INNER JOIN Catalog ON CatalogImage.catalogId = Catalog.id
                            WHERE Catalog.name = :catalogue";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['catalogue' => $CatalogueChoisie]);
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);
                $images1 = $images[0];
                

                $query = "  SELECT Bank.dir AS bkDir FROM Bank
                INNER JOIN Image ON Bank.id = Image.bankId
                WHERE Image.name= '". $images1['imgName']."'";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $bank = $stmt->fetch(PDO::FETCH_ASSOC);
                
                foreach ($images as $image){
                    echo '<option value="' . htmlspecialchars($image['imgName']) . '">' . htmlspecialchars($image['imgName']) . '</option>';
                            
                ?>
            <div class="container">
                <div class="card" style="width: 18rem;">
                    <img src="<?php echo "../Images/".htmlspecialchars($bank['bkDir'])."/".htmlspecialchars($image['imgName'])?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <h5 class="card-title">l'Everest</h5>
                        <p class="card-text">Voici une image de ....</p>
                        <a href="#" class="btn btn-primary">Retour</a>
                    </div>
                </div>
            </div>
                <?php
                }

            }
            else if (isset($_POST['validerCatalogue']) && $_POST['catalogue'] == "defaut"){
                echo "Veuillez selectionner un catalogue !";
            }
        ?>

        <?php   
        
        //On affiche les images séléctionné
        
        if (isset($_POST['validerImage']) && $_POST['image'] != "defaut") {

            // Requête pour récupérer l'emplacement ou est sauvegardé l'image
            $query = "  SELECT Bank.name AS bkName FROM Bank
                        INNER JOIN Image ON Bank.id = Image.bankId
                        WHERE Image.name= '". $ImageChoisie."'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $bank = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<div class='image-container'>";
            
            echo '<canvas id="myCanvas" width="800" height="600">';
            echo '<img id="dynamic-image" src="../images/'.$bank['bkName'].'/'.$ImageChoisie.'"/></canvas>';


            echo "</div>";
            
        }else if (isset($_POST['validerImage']) && $_POST['image'] == "defaut"){
            echo "Veuillez selectionner une image !";
        }
        ?>


