<?php
header("Content-Type: application/json");
require_once 'vendor/autoload.php';
require_once('bdd.php'); // Assurez-vous que vous avez inclus votre fichier de connexion à la base de données

// Vérifiez si la demande est une demande POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérez les données de la demande
    $data = json_decode(file_get_contents("php://input"));

    // Vérifiez si les données nécessaires sont présentes
    if (isset($data->nom) && isset($data->prenom) && isset($data->nom_utilisateur) && isset($data->email) && isset($data->mot_de_passe)) {
        $nom = $data->nom;
        $prenom = $data->prenom;
        $nom_utilisateur = $data->nom_utilisateur;
        $email = $data->email;
        $mot_de_passe = hash('sha256', $data->mot_de_passe); // Hachage SHA-256 du mot de passe

        // Requête SQL pour insérer un nouvel utilisateur dans la base de données
        $sql = "INSERT INTO user (nom, prenom, nom_utilisateur, email, mot_de_passe) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("sssss", $nom, $prenom, $nom_utilisateur, $email, $mot_de_passe);
            if ($stmt->execute()) {
                http_response_code(201); // Code 201 pour "Created"
                echo json_encode(array("message" => "Utilisateur enregistré avec succès."));
            } else {
                http_response_code(500); // Code 500 pour "Internal Server Error"
                echo json_encode(array("message" => "Erreur lors de l'enregistrement de l'utilisateur : " . $stmt->error));
            }
            $stmt->close();
        } else {
            http_response_code(500); // Code 500 pour "Internal Server Error"
            echo json_encode(array("message" => "Erreur de préparation de la requête."));
        }
        $conn->close();
    } else {
        http_response_code(400); // Code 400 pour "Bad Request"
        echo json_encode(array("message" => "Données incomplètes."));
    }
} else {
    http_response_code(405); // Code 405 pour "Method Not Allowed"
    echo json_encode(array("message" => "Méthode non autorisée."));
}
?>
