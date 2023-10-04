<?php
header("Content-Type: application/json");
require_once 'vendor/autoload.php';
require_once 'bdd.php'; // Assurez-vous d'avoir inclus votre fichier de connexion à la base de données

// Vérifiez si la demande est une demande POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérez les données de la demande
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $id_categorie = $_POST["id_categorie"];
    $latitude = $_POST["latitude"];
    $longitude = $_POST["longitude"];
    $priorite = "Basse"; // Priorité par défaut
    $statut = "Nouveau"; // Statut par défaut
    $utilisateur_id = $_POST["utilisateur_id"];

    // Obtenir la date et l'heure actuelles au format MySQL
    $date_creation = date('Y-m-d H:i:s');

    // Gérer le fichier image
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $imageData = file_get_contents($_FILES['image']['tmp_name']);

        // Requête SQL pour insérer le ticket avec l'image
        $sql = "INSERT INTO tickets (titre, description, id_categorie, latitude, longitude, priorite, statut, date_creation, utilisateur_id, image_data) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssiddissib", $titre, $description, $id_categorie, $latitude, $longitude, $priorite, $statut, $date_creation, $utilisateur_id, $imageData);
            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(array("message" => "Ticket créé avec succès."));
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Erreur lors de la création du ticket."));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Erreur lors de la préparation de la requête de création du ticket."));
        }
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Aucun fichier image téléchargé ou erreur lors de l'envoi."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
