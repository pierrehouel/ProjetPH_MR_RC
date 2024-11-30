<?php include 'database.php';


ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

$securite = "akunamatata";

//on récupère le role et l'id de l'utilisateur connecté
if (isset($_GET['role']) && isset($_GET['id'])) {
    $role_chiffre = $_GET['role'];
    $id_chiffre = $_GET['id'];

    $roleE = openssl_decrypt(base64_decode($role_chiffre), 'AES-128-ECB', $securite);
    if ($roleE == 1){
        $role = "editeur";
    }else if($roleE == 2){
        $role = "non-editeur";
    }

    $idE = openssl_decrypt(base64_decode($id_chiffre), 'AES-128-ECB', $securite);

    //On transmet le role de l'utilisateur pour les autres pages
    $role_chiffreT = base64_encode(openssl_encrypt($roleE, 'AES-128-ECB', $securite));
    $id_chiffreT = base64_encode(openssl_encrypt($idE, 'AES-128-ECB', $securite));

    $lienI = "index.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);

} else{
    //Si l'utilisateur n'est pas connecté il sera en non-editeur par défaut
    $role = "Pas connecté";

    $roleE = 2;
    $role_chiffreT = base64_encode(openssl_encrypt($roleE, 'AES-128-ECB', $securite));

    $lienI = "index.php?role=" . urlencode($role_chiffreT);
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

    <link rel="stylesheet" href="page2.css">
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visualiser</title>
   
</head>
<body>
    <ul class="navbar">
        <li class="active"><a href="<?php echo $lienI;?>">Retour</a></li>
    </ul>
    
    
    <p>l'utilisateur est: <?php echo $role;?></p>
    <p>l'utilisateur ID est: <?php echo $idE;?></p>
    
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
            $query = "  SELECT Bank.name AS bkName FROM Bank
                        INNER JOIN Image ON Bank.id = Image.bankId
                         WHERE Image.name= '". $ImageChoisie."'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $bank = $stmt->fetch(PDO::FETCH_ASSOC);

            echo "<div class='image-container'>";
            echo '<img id="dynamic-image" src="../images/'.$bank['bkName'].'/'.$ImageChoisie.'"/>';
            echo "</div>";

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

            }else if (isset($_POST['validerImage']) && $_POST['image'] == "defaut"){
                echo "Veuillez selectionner une image !";
            }  

         ?>
</body>
</html>
<?php
// Initialisation de $donnees
$donnees = [];

try {
    // Requête SQL
    $query = "SELECT points FROM Label"; // Table et colonnes réelles
    $stmt = $pdo->prepare($query);
    $stmt->execute();
    $donnees = $stmt->fetchAll(PDO::FETCH_ASSOC); // On récupère tout
} catch (PDOException $e) {
    echo "Erreur de connexion ou de requête : " . $e->getMessage();
    exit; // Stoppe l'exécution si erreur
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Données depuis la base</title>
</head>
<body>
    <h1>Les données de ma base</h1>

    <?php if (!empty($donnees)): ?>
        <table border="1">
            <thead>
                <tr>
                    <th>étiquettes</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($donnees as $ligne): ?>
                    <tr>
                        <td><?= htmlspecialchars($ligne['points']) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Aucune donnée à afficher.</p>
    <?php endif; ?>
</body>
</html>