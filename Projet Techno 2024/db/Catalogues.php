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

?>

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
                <li class="active"><a href="<?php echo $lienI;?>">Retour</a></li>
            </ul>

            <p>l'utilisateur est: <?php echo $role;?></p>
            <p>l'utilisateur ID est: <?php echo $idE;?></p>

            <h1><?php echo "Créer un Catalogue"?></h1>      

                <form method="POST">
                <label for="input-text">Nom nouveau catalogue :</label>
                <input type="text" id="input-text" name="user_input" required>
                <button type="submit" name="Creer">Créer</button>
                </form>
                
                <?php
                // Peut etre mettre cela dans un autre fichier creationCatalogues.php ???
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["Creer"])) {
                // Récupération de la donnée saisie
                $userInput = $_POST['user_input'];

                // Vérification que la donnée n'est pas vide
                if (!empty($userInput)) {
                    try {
                        // Préparation de la requête SQL
                        $stmt = $pdo->prepare("INSERT INTO Catalog (userAccoundId,name) VALUES (3,:user_input)");
                        $stmt->bindParam(':user_input', $userInput);
                        $stmt->execute();
                        
                        $stmt = $pdo->prepare("INSERT INTO Bank (dir,name) VALUES (:user_input,:user_input)");
                        $stmt->bindParam(':user_input', $userInput);
                        

                        // Exécution de la requête
                        $stmt->execute();

                        // Chemin du dossier à créer
                        $chemin = "../Images/$userInput";

                        // Vérifier si le dossier existe déjà
                        if (!file_exists($chemin)) {
                            // Créer le dossier avec des permissions spécifiques (par exemple, 0755)
                            if (mkdir($chemin, 0777, true)) {
                                echo "Le dossier a été créé avec succès : " . $chemin;
                            } else {
                                echo "Échec de la création du dossier.";
                            }
                        } else {
                            echo "Le dossier existe déjà.";
                        }

                        echo "Donnée insérée avec succès !";
                    } catch (PDOException $e) {
                        echo "Erreur lors de l'insertion : " . $e->getMessage();
                    }
                    } else {
                        echo "Veuillez entrer une valeur.";
                    }

                        // Redirection vers la même page après traitement
                        header('Location: ' . $_SERVER['PHP_SELF']);
                        exit; // Important : arrêter l'exécution après la redirection

                    
                } ?>

                <?php $catalogues = [];
                try {
                    $stmt = $pdo->query("SELECT id, name FROM Catalog");
                    $catalogues = $stmt->fetchAll(PDO::FETCH_ASSOC);
                } catch (PDOException $e) {
                    echo "Erreur lors de la récupération des catalogues : " . $e->getMessage();
                }
                ?>

                <h1>Supprimer un catalogue</h1>
                <form method="POST">
                    <label for="catalogue-select">Choisissez un catalogue :</label>
                    <select id="catalogue-select" name="catalogue_id" required>
                        <option value="">-- Sélectionnez un catalogue --</option>
                        <?php foreach ($catalogues as $catalogue): ?>
                            <option value="<?= $catalogue['id'] ?>"><?= htmlspecialchars($catalogue['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" name="Supp">Supprimer</button>
                    </form>
                

                <?php
                // Vérification que l'ID du catalogue est transmis

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST["Supp"])) {
                    $catalogueId = $_POST['catalogue_id'];


                    try {
                        // Suppression du catalogue et la bank

                        $stmt = $pdo->prepare("SELECT * FROM CatalogImage WHERE catalogId = :id");
                        $stmt->bindParam(':id', $catalogueId, PDO::PARAM_INT);
                        $stmt->execute();
                        $allCataImg = $stmt->fetch(PDO::FETCH_ASSOC);

                        $stmt = $pdo->prepare("DELETE FROM CatalogImage WHERE catalogId = :id");
                        $stmt->bindParam(':id', $catalogueId, PDO::PARAM_INT);
                        $stmt->execute();

                        $stmt = $pdo->prepare("DELETE FROM Image WHERE id = '". $allCataImg['imageId']."'");
                        $stmt->execute();

                        $idBank = (int)$catalogueId - 24;
                        $stmt = $pdo->prepare("DELETE FROM Bank WHERE id = '". $idBank."'");
                        $stmt->execute();

                        $stmt = $pdo->prepare("DELETE FROM Catalog WHERE id = :id");
                        $stmt->bindParam(':id', $catalogueId, PDO::PARAM_INT);
                        $stmt->execute();
                        

                        if ($stmt->execute()) {
                            header("Location:Catalogues.php");
                            echo "Le catalogue a été supprimé avec succès et les données associées ont été supprimées de la base de données.";
                            exit();
                        } else {
                            echo "Erreur lors de la suppression.";
                        }
                    } catch (PDOException $e) {
                        echo "Erreur : " . $e->getMessage();
                    }
                } else {
                    echo "Veuillez selectionner un catalogue !";
                }
                
                ?>


                <h1><?php echo "Ajouter des Images"?></h1>      

                    <form method="POST" enctype="multipart/form-data">
                        <label for="image">Choisissez une image :</label>
                        <input type="file" name="image" id="image-upload" required>

                        <select id="catalogue-select" name="catalogue" required>
                            <option value="">-- Sélectionnez un catalogue --</option>
                            <?php foreach ($catalogues as $catalogue): ?>
                                <option value="<?= $catalogue['id'] ?>"><?= htmlspecialchars($catalogue['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                        <button type="submit" name="ImageVal">Ajouter</button>
                    </form>

                    <?php
                    // Vérification que le formulaire a été soumis
                    if (isset($_POST['ImageVal'])) {
                        // Récupérer le fichier
                        $image = $_FILES['image'];
                        
                        $nomF = $_FILES['image']['name'];

                        //Avoir le id de la bank
                        $bankId = (int)$catalogue['id'] - 24;

                        // Vérification des erreurs
                        if ($image['error'] !== 0) {
                            die("Erreur lors du téléchargement de l'image.");
                        }

                        // Vérification du type de fichier (facultatif)
                        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
                        if (!in_array($image['type'], $allowedTypes)) {
                            die("Type de fichier non autorisé. Seuls JPEG, PNG, et GIF sont acceptés.");
                        }

                        //Récup du dir dans la bank
                        $stmt = $pdo->prepare("SELECT dir FROM Bank WHERE id = :id");
                        $stmt->bindParam(':id', $bankId, PDO::PARAM_INT);
                        $stmt->execute();
                        $dirBank = $stmt->fetch(PDO::FETCH_ASSOC);

                        //Chemin temporaire du ficher
                        $ficherTemporaire= $_FILES['image']['tmp_name'];
                        
                        // Construire le chemin final 
                        $cheminFinal= "../Images/".$dirBank['dir']."/".$nomF;

                        // Lire le contenu du fichier en binaire
                        $imageData = file_get_contents($image['tmp_name']);

                        // Insertion dans la base de données modifie encore
                        try {
                            $stmt = $pdo->prepare("INSERT INTO Image (name, bankId) VALUES (:name, :bankId)");
                            $stmt->bindParam(':name', $nomF);
                            $stmt->bindParam(':bankId', $bankId);
                            $stmt->execute();
                            
                            $stmt = $pdo->prepare("SELECT id FROM Image WHERE name = :nom");
                            $stmt->bindParam(':nom', $nomF);
                            $stmt->execute();
                            $imgId = $stmt->fetch(PDO::FETCH_ASSOC);
                            
                            $stmt = $pdo->prepare("INSERT INTO CatalogImage (catalogId, imageId) VALUES (:catalogId, :imgId)");
                            $stmt->bindParam(':catalogId', $catalogue['id']);
                            $stmt->bindParam(':imgId', $imgId['id']);
                            $stmt->execute();

                            move_uploaded_file($ficherTemporaire, $cheminFinal);

                            echo "L'image a été téléchargée et enregistrée avec succès dans la base de données.";
                        } catch (PDOException $e) {
                            echo "Erreur lors de l'insertion dans la base de données : " . $e->getMessage();
                        }
                    } else {
                        echo "Aucune image envoyée.";
                    }
                    ?>
            </div>
            
        </div>

        
    </body>

    <script type="text/php" src="index.php"></script>



</html>