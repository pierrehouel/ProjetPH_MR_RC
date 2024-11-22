
<?php
$host = 'localhost'; // adresse du serveur SQL
$dbname = 'nom_de_la_base'; // nom de la base de données
$username = 'nom_utilisateur'; // nom d'utilisateur SQL
$password = 'mot_de_passe'; // mot de passe SQL

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "Connexion réussie !";
} catch (PDOException $e) {
    echo "Erreur de connexion : " . $e->getMessage();
}
?>
   <h1>Données de ma base SQL</h1>
    <?php
    include 'connect.php';

    $query = $pdo->query("SELECT * FROM ma_table"); // Requête pour obtenir des données

    while ($row = $query->fetch()) {
        echo "<p>" . $row['nom_colonne'] . "</p>"; // Affiche une colonne spécifique
    }
    ?>
