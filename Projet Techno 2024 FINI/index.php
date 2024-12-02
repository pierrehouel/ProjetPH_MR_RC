<?php include 'db/database.php';

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

        if ($roleE == 1){
            $role = "editeur";
            $lienE = "Edition.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);
        }else if($roleE == 2){
            $role = "non-editeur";
            $lienE = "#";
        }


        

        $lienV = "Visualiser.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);
        $lienC = "Catalogues.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);

    } else{
        //Si l'utilisateur n'est pas connecté il sera en non-editeur par défaut
        $role = "Pas connecté";


        $lienE = "#";
        $lienV = "Visualiser.php";
        $lienC = "#";
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
<html>
    <head>
        <title>Accueil</title>
        <link rel="stylesheet" href="css/page.css">
        <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Roboto:wght@400&display=swap" rel="stylesheet">
    </head>

<body>
    <ul class="navbar">
        <li class="active"><a>Sommet</a></li>
        <li class="left"><a href="<?php echo $lienC; ?>">Catalogue</a></li>
        <li class="right"><a href="<?php echo $lienE; ?>">Édition</a></li>
        <li class="right"><a href="<?php echo $lienV; ?>">Visualiser</a></li>

        <li class="right"><a href="Connexion.php">Connexion</a></li>
    </ul>

    <div class="content">
        <p class="">l'utilisateur est: <?php echo $role;?></p>
        <p class="invisible">l'utilisateur ID est: <?php echo $idE;?></p>
    </div>
    <h1>Bonjour !</h1>
    <p class="presentation"> Ce projet porte sur le développement d’un outil nommé SOMMETS pour le catalogage et
        l'étiquetage d’images. La principale fonctionnalité de cet outil est de permettre à un éditeur
        d'étiqueter des régions d’intérêt d’une image et d’associer à chaque étiquette une description
        en format HTML.
    </p>

    
    <?php
            //on récupère tout les nom de catalogue dans la BDD
            $query = "SELECT name FROM Catalog";
            $stmt = $pdo->query($query);
            $catalog = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>
    <!-- // liste déroulante pour les catégories -->
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
            $images1 = $images[0];

            $query = "  SELECT Bank.dir AS bkDir FROM Bank
                        INNER JOIN Image ON Bank.id = Image.bankId
                        WHERE Image.name= '". $images1['imgName']."'";
            $stmt = $pdo->prepare($query);
            $stmt->execute();
            $bank = $stmt->fetch(PDO::FETCH_ASSOC);
                
            foreach ($images as $image){
    ?>

                <div class="container">
                    <div class="card" style="width: 18rem;">
                        <img src="<?php echo "Images/".htmlspecialchars($bank['bkDir'])."/".htmlspecialchars($image['imgName'])?>" class="card-img-top" alt="...">
                    </div>
                </div>

    <?php
            }

        //Si un image n'a pas été séléctionné
        }else if (isset($_POST['validerCatalogue']) && $_POST['catalogue'] == "defaut"){
            echo "Veuillez selectionner un catalogue !";
        }
    ?>

    <ul class="navbar-bas">
        <p>Sommet</p>
    </ul>
</body>
</html>
