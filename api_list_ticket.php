<?php
header("Content-Type: application/json");
require_once('bdd.php'); // Assurez-vous que vous avez inclus votre fichier de connexion à la base de données

// Vérifiez si la demande est une demande GET
if ($_SERVER["REQUEST_METHOD"] === "GET") {
    // Récupérez le nom de l'utilisateur à partir des paramètres de la requête
    $nom_utilisateur = $_GET["nom_utilisateur"];

    // Requête SQL pour obtenir l'ID de l'utilisateur en fonction de son nom d'utilisateur
    $sql_user_id = "SELECT id FROM user WHERE nom_utilisateur = ?";
    if ($stmt_user_id = $conn->prepare($sql_user_id)) {
        $stmt_user_id->bind_param("s", $nom_utilisateur);
        if ($stmt_user_id->execute()) {
            $stmt_user_id->bind_result($utilisateur_id);
            $stmt_user_id->fetch();
            $stmt_user_id->close();

            // Vérifiez si un utilisateur avec ce nom d'utilisateur existe
            if (!$utilisateur_id) {
                http_response_code(404);
                echo json_encode(array("message" => "Utilisateur non trouvé."));
                exit;
            }

            // Requête SQL pour récupérer les tickets de l'utilisateur en fonction de son ID
            $sql_tickets = "SELECT * FROM tickets WHERE utilisateur_id = ?";
            if ($stmt_tickets = $conn->prepare($sql_tickets)) {
                $stmt_tickets->bind_param("i", $utilisateur_id);
                if ($stmt_tickets->execute()) {
                    $result = $stmt_tickets->get_result();

                    // Créez un tableau pour stocker les tickets de l'utilisateur
                    $tickets = array();

                    while ($row = $result->fetch_assoc()) {
                        $tickets[] = $row;
                    }

                    http_response_code(200);
                    echo json_encode($tickets);
                } else {
                    http_response_code(500);
                    echo json_encode(array("message" => "Erreur lors de l'exécution de la requête des tickets."));
                }
            } else {
                http_response_code(500);
                echo json_encode(array("message" => "Erreur lors de la préparation de la requête des tickets."));
            }
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Erreur lors de l'exécution de la requête pour obtenir l'ID de l'utilisateur."));
        }
    } else {
        http_response_code(500);
        echo json_encode(array("message" => "Erreur lors de la préparation de la requête pour obtenir l'ID de l'utilisateur."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
