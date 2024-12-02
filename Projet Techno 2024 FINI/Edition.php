<?php include 'db/database.php';


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);


$securite = "akunamatata";

//on récupère le role et l'id de l'utilisateur connecté
if (isset($_GET['role']) && isset($_GET['id'])) {
    $role_chiffre = $_GET['role'];
    $id_chiffre = $_GET['id'];

    $roleE = openssl_decrypt(base64_decode($role_chiffre), 'AES-128-ECB', $securite);

    $idE = openssl_decrypt(base64_decode($id_chiffre), 'AES-128-ECB', $securite);

    //On transmet le role de l'utilisateur pour les autres pages
    $role_chiffreT = base64_encode(openssl_encrypt($roleE, 'AES-128-ECB', $securite));
    $id_chiffreT = base64_encode(openssl_encrypt($idE, 'AES-128-ECB', $securite));

    $lienI = "index.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);

}


$CatalogueChoisie = null;
$ImageChoisie = null;
$DonneeRecu = null;

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

        <link rel="stylesheet" href="css/page2.css">
        <meta charset="UTF-8">
        <title>Edition</title>
    
    </head>
    <body>
        <ul class="navbar">
            <li class="active"><a href="<?php echo $lienI;?>">Retour</a></li>
        </ul>
        
        <p class="invisible">l'utilisateur est: <?php echo $role;?></p>
        <p class="invisible">l'utilisateur ID est: <?php echo $idE;?></p>
        
        <!-- // liste déroulante pour les catégorie -->
        <?php
            $query = "SELECT name FROM Catalog";
            $stmt = $pdo->query($query);
            $catalog = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <form class="form" method='post'>
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

            
            //On affiche l'image séléctionné
            
            if (isset($_POST['validerImage']) && $_POST['image'] != "defaut") {
                
                // Requête pour récupérer l'emplacement ou est sauvegardé l'image
                $query = "  SELECT Bank.name AS bkDir FROM Bank
                            INNER JOIN Image ON Bank.id = Image.bankId
                            WHERE Image.name= '". $ImageChoisie."'";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $bank = $stmt->fetch(PDO::FETCH_ASSOC);
        ?>
                <canvas id="myCanvas" ></canvas>
            
                <button id="final" type="submit" name="validerEtiquette">Enregistrer l'étiquette</button>

        <?php
                //récup id de l'image selectionné
                $query = "  SELECT Image.id AS imgId FROM Image
                            WHERE Image.name= '". $ImageChoisie."'";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $ID_Img = $stmt->fetch(PDO::FETCH_ASSOC);

                //Récup l'id du catalogue selectionné
                $query = "  SELECT CatalogImage.catalogId AS cataId FROM CatalogImage
                            WHERE CatalogImage.imageId= '". $ID_Img['imgId']."'";
                $stmt = $pdo->prepare($query);
                $stmt->execute();
                $ID_Cata = $stmt->fetch(PDO::FETCH_ASSOC);
                
        ?>
                
                <!-- elle ne s'affiche pas -->

                <p class="invisible" id="coordPts"></p>
                <p class="invisible" id="nomEtiq"></p>
                <p class="invisible" id="descEtiq"></p>
                <p class="invisible" id="imgId"><?php echo $ID_Img["imgId"]?></p>
                <p class="invisible" id="cataId"><?php echo $ID_Cata["cataId"]?></p>

                <p id="imgdir"><?php echo $bank['bkDir']?></p>
                <p id="imgname"><?php echo $ImageChoisie?></p>

                <!-- pour convertir du html au PHP -->
                <form id="textForm" method="POST">
                    <input type="hidden" name="text1" id="hiddenText1">
                    <input type="hidden" name="text2" id="hiddenText2">
                    <input type="hidden" name="text3" id="hiddenText3">
                    <input type="hidden" name="text4" id="hiddenText4">
                    <input type="hidden" name="text5" id="hiddenText5">
                    
                    <button id="final" type="submit" name="valideDonnee">Envoyer au serveur</button>
                </form>

                <script>
                    document.getElementById('textForm').addEventListener('submit', function (event) {
                        document.getElementById('hiddenText1').value = document.getElementById('cataId').innerText;
                        document.getElementById('hiddenText2').value = document.getElementById('imgId').innerText;
                        document.getElementById('hiddenText3').value = document.getElementById('nomEtiq').innerText;
                        document.getElementById('hiddenText4').value = document.getElementById('descEtiq').innerText;
                        document.getElementById('hiddenText5').value = document.getElementById('coordPts').innerText;
                    });
                </script>

        <?php    
                }else if (isset($_POST['validerImage']) && $_POST['image'] == "defaut"){
                    echo "Veuillez selectionner une image !";
                }  

                if (isset($_POST['valideDonnee'])) {
                    $catag_Id = isset($_POST['text1']) ? $_POST['text1'] : '';
                    $img_Id = isset($_POST['text2']) ? $_POST['text2'] : '';
                    $name = isset($_POST['text3']) ? $_POST['text3'] : '';
                    $description = isset($_POST['text4']) ? $_POST['text4'] : '';
                    $points = isset($_POST['text5']) ? $_POST['text5'] : '';

                    try {
                        // Préparer une requête pour insérer les données
                        $query = "  INSERT INTO Label (catalogId , imageId , name, description, points) 
                                    VALUES (:catalogID, :imageId, :nom, :description, :points)";
                        $stmt = $pdo->prepare($query);
            
                        // Exécuter la requête avec les données
                        $stmt->execute([
                            ':catalogID' => $catag_Id,
                            ':imageId' => $img_Id,
                            ':nom' => $name,
                            ':description' => $description,
                            ':points' => $points
                        ]);
            
                    } catch (PDOException $e) {
                        // Gestion des erreurs
                        echo "nop";
                    }

                }
        ?>

        <!-- appel du fichier javascript -->
        <script type="text/javascript" src="js/edition.js"></script>
    </body>
</html>
