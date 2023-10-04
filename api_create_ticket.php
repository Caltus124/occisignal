<?php
header("Content-Type: application/json");
require_once 'vendor/autoload.php';
require_once('bdd.php'); // Assurez-vous que vous avez inclus votre fichier de connexion à la base de données

// Vérifiez si la demande est une demande POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérez les données de la demande
    $data = json_decode(file_get_contents("php://input"));

    $titre = $data->titre;
    $description = $data->description;
    $date_creation = date('Y-m-d H:i:s');
    $date_modification = date('Y-m-d H:i:s');
    $utilisateur_id = $data->utilisateur_id;
    $categorie_id = $data->categorie_id;
    $longitude = $data->longitude;
    $latitude =  $data->latitude;
    $date_creation = date('Y-m-d H:i:s');
    $date_modification = date('Y-m-d H:i:s');
    $image_data = $data->image_data;
 


    // Requête SQL pour insérer un nouvel utilisateur dans la base de données
    $sql = "INSERT INTO tickets (titre, description, date_creation, date_modification, utilisateur_id, categorie_id, longitude, latitude, image_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("ssssiidds", $titre, $description, $date_creation, $date_modification, $utilisateur_id, $categorie_id, $longitude, $latitude, $image_data);
        if ($stmt->execute()) {
            http_response_code(201); // Code 201 pour "Created"
            echo json_encode(array("message" => "Ticket enregistré avec succès."));
        } else {
            http_response_code(500); // Code 500 pour "Internal Server Error"
            echo json_encode(array("message" => "Erreur lors de l'enregistrement du ticket : " . $stmt->error));
        }
        $stmt->close();
    } else {
        http_response_code(500); // Code 500 pour "Internal Server Error"
        echo json_encode(array("message" => "Erreur de préparation de la requête."));
    }
    $conn->close();

} else {
    http_response_code(405); // Code 405 pour "Method Not Allowed"
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
