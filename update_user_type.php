<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["userId"]) && isset($_POST["newType"])) {
    $userId = $_POST["userId"];
    $newType = $_POST["newType"];


    // Requête SQL pour mettre à jour le type d'utilisateur
    $sql = "UPDATE user SET type_utilisateur = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $newType, $userId);

    if ($stmt->execute()) {
        echo "Mise à jour réussie.";
    } else {
        echo "Erreur lors de la mise à jour : " . $stmt->error;
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    echo "Requête incorrecte.";
}
?>
