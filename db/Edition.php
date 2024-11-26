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

    $lien = "index.php?role=" . urlencode($entier_chiffre);

} else {
    $role = "Pas connecté !";
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
    <ul class="navbar">
        <li class="active"><a href="<?php echo $lien;?>"><?php echo "Retour"?></a></li>
    </ul>
    
    <p>l'utilisateur est: <?php echo $role;?></p>
    
    <!-- // liste déroulante pour les catégorie -->
        <?php
            $query = "SELECT name FROM catalog";
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
                $query = "  SELECT image.name AS imgName FROM image
                            INNER JOIN catalogimage ON image.id = catalogimage.imageId
                            INNER JOIN catalog ON catalogimage.catalogId = catalog.id
                            WHERE catalog.name = :catalogue";
                $stmt = $pdo->prepare($query);
                $stmt->execute(['catalogue' => $CatalogueChoisie]);
                $images = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (!empty($images)) {  ?>
                    <form method="post">
                        <input type="hidden" name="catalogue" value="<?= $selectedCatalogue ?>">
                        <label for="image">Choisissez une image dans "<?echo $CatalogueChoisie ;?>" : </label>
                        <select name="image" id="image">
                        <option value='defaut'>--Sélectionnez une image--</option>
                            <?php 
                                foreach ($images as $image){
                                    echo '<option value="' . htmlspecialchars($image['imgName']) . '">' . htmlspecialchars($image['imgName']) . '</option>';
                                }
                            ?>
                        </select>
                        <button type="submit" name="validerImage">Afficher l'image</button>
                    </form>
                    <?php
                }
            }else if (isset($_POST['validerCatalogue']) && $_POST['catalogue'] == "defaut"){
                echo "Veuillez selectionner un catalogue !";
            }
        ?>

        <?php   
        
        //On affiche l'image séléctionné
        
        if (isset($_POST['validerImage']) && $_POST['image'] != "defaut") {

            // Requête pour récupérer l'emplacement ou est sauvegardé l'image
            $query = "  SELECT bank.name AS bkName FROM bank
                        INNER JOIN image ON bank.id = image.bankId
                        WHERE image.name= '". $ImageChoisie."'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $bank = $stmt->fetch(PDO::FETCH_ASSOC);
            echo "<div class='image-container'>";
            echo '<img id="dynamic-image" src="../images/'.$bank['bkName'].'/'.$ImageChoisie.'"/>';
            echo "</div>";
            
        }else if (isset($_POST['validerImage']) && $_POST['image'] == "defaut"){
            echo "Veuillez selectionner une image !";
        }
        ?>


    <!-- appel du fichier javascript -->
    <script type="text/javascript" src="../js/edition.js"></script>
</body>
</html>
