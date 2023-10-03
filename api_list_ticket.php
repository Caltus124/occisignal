<?php
header("Content-Type: application/json");
require_once('bdd.php'); // Assurez-vous que vous avez inclus votre fichier de connexion à la base de données

// Vérifiez si la demande est une demande GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Récupérez l'ID de l'utilisateur à partir des paramètres de la requête
    $utilisateur_id = $_GET["utilisateur_id"];

    // Requête SQL pour récupérer les tickets de l'utilisateur en fonction de son ID
    $sql = "SELECT * FROM tickets WHERE utilisateur_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $utilisateur_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();

            // Créez un tableau pour stocker les tickets de l'utilisateur
            $tickets = array();

            while ($row = $result->fetch_assoc()) {
                $tickets[] = $row;
            }

            http_response_code(200);
            echo json_encode($tickets);
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Erreur lors de l'exécution de la requête."));
        }
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Erreur lors de la préparation de la requête."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
