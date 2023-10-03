<?php
header("Content-Type: application/json");
require_once 'vendor/autoload.php';
require_once('bdd.php'); // Assurez-vous que vous avez inclus votre fichier de connexion à la base de données

// Vérifiez si la demande est une demande POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérez les données de la demande
    $data = json_decode(file_get_contents("php://input"));

    // Vérifiez si les données nécessaires sont présentes
    if (isset($data->nom_utilisateur) && isset($data->mot_de_passe)) {
        $nom_utilisateur = $data->nom_utilisateur;
        $mot_de_passe = $data->mot_de_passe;

        // Hachez le mot de passe en utilisant SHA-256
        $mot_de_passe_hache = hash('sha256', $mot_de_passe); // Hachage en SHA-256

        // Requête SQL pour vérifier les informations d'authentification
        $sql = "SELECT id, nom_utilisateur FROM user WHERE nom_utilisateur = ? AND mot_de_passe = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $nom_utilisateur, $mot_de_passe_hache);
            $stmt->execute();
            $stmt->store_result();

            if ($stmt->num_rows > 0) {
                $stmt->bind_result($user_id, $username);
                $stmt->fetch();

                $randomBytes = random_bytes(32);

                // Générez un jeton d'authentification (JWT) pour l'utilisateur
                $key = "4813494d137e1631bba301d5acab6e7bb7aa74ce1185d456565ef51d737677b2"; // Remplacez par votre clé secrète
                $token_payload = array(
                    "user_id" => $user_id,
                    "username" => $username,
                    "exp" => time() + 3600 // Le jeton expire dans 1 heure (3600 secondes)
                );

                $jwt_token = JWT::encode($token_payload, $key, 'HS256');

                http_response_code(200);
                echo json_encode(array("message" => "Authentification réussie", "token" => $jwt_token));
            } else {
                http_response_code(401);
                echo json_encode(array("message" => "Nom d'utilisateur ou mot de passe incorrect."));
            }

            $stmt->close();
        } else {
            http_response_code(500);
            echo json_encode(array("message" => "Erreur de préparation de la requête."));
        }

        $conn->close();
    } else {
        http_response_code(400);
        echo json_encode(array("message" => "Données incomplètes."));
    }
} else {
    http_response_code(405);
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
