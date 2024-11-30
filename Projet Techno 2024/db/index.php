<?php include 'database.php';

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

    $lienE = "Edition.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);
    $lienV = "Visualiser.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);
    $lienC = "Catalogues.php?role=" . urlencode($role_chiffreT)."&id=".urlencode($id_chiffreT);

} else{
    //Si l'utilisateur n'est pas connecté il sera en non-editeur par défaut
    $role = "Pas connecté";

    $roleE = 2;
    $role_chiffreT = base64_encode(openssl_encrypt($roleE, 'AES-128-ECB', $securite));

    $lienE = "Edition.php?role=" . urlencode($role_chiffreT);
    $lienV = "Visualiser.php?role=" . urlencode($role_chiffreT);
    $lienC = "Catalogues.php?role=" . urlencode($role_chiffreT);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Accueil</title>
    <link rel="icon" href="../images/Sommet.png" type="image/x-icon">
    <link rel="stylesheet" href="page.css">
</head>

<body>
    <ul class="navbar">
        <li class="active"><a href="#">Sommet</a></li>
        <li class="left"><a href="<?php echo $lienC; ?>">Catalogue</a></li>
        <li class="right"><a href="<?php echo $lienE; ?>">Édition</a></li>
        <li class="right"><a href="<?php echo $lienV; ?>">Visualiser</a></li>
        <li class="right"><a href="Connexion.php">Connexion</a></li>
    </ul>

    <div class="content">
        <p>l'utilisateur est: <?php echo $role;?></p>
        <p>l'utilisateur ID est: <?php echo $idE;?></p>
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
