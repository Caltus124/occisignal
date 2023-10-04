<?php
// Connexion à la base de données (à adapter selon votre configuration)
require_once('bdd.php');


// Requête SQL pour sélectionner toutes les catégories
$sql = "SELECT * FROM categories";
$result = $conn->query($sql);

// Tableau pour stocker les catégories
$categories = array();

// Vérification des résultats de la requête
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $categories[] = array(
            "id" => $row["id"],
            "titre" => $row["titre"],
            "description" => $row["description"]
        );
    }
}

// Fermeture de la connexion à la base de données
$conn->close();

// Conversion du tableau en format JSON
$json_response = json_encode($categories);

// Envoi de la réponse JSON
header('Content-Type: application/json');
echo $json_response;
?>
