
<?php
$host = 'localhost'; // adresse du serveur SQL
$dbname = 'projettechno_2024'; // nom de la base de données
$username = '386539'; // nom d'utilisateur SQL
$password = 'Projet2024'; // mot de passe SQL

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

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Récupérer les coordonnées envoyées en JSON
    $points = json_decode($_POST['points'], true);

    // Vous pouvez maintenant utiliser ces points pour effectuer un traitement,
    // comme obtenir des informations liées à cette zone ou stocker dans une base de données.
    
    // Exemple : Affichage des points reçus
    echo json_encode([
        'status' => 'success',
        'points' => $points,
        'message' => 'Données reçues et traitées.',
    ]);
}
?>
