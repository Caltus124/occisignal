<?php
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["userId"]) && isset($_POST["newType"])) {
    $userId = $_POST["userId"];
    $newType = $_POST["newType"];

    // Connexion à la base de données
    $conn = new mysqli("localhost", "caltus", "root", "signalement");

    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Requête SQL pour mettre à jour le type d'utilisateur
    $updateSql = "UPDATE user SET type_utilisateur = ? WHERE id = ?";
    $stmt = $conn->prepare($updateSql);
    $stmt->bind_param("si", $newType, $userId);

    if ($stmt->execute()) {
        echo "Mise à jour réussie !";
    } else {
        echo "Erreur lors de la mise à jour : " . $stmt->error;
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    echo "Requête invalide.";
}
?>
