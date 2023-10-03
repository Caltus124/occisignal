<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}


// Vérification de la présence de l'ID de l'utilisateur à supprimer
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Requête SQL pour supprimer l'utilisateur
    $sql = "DELETE FROM user WHERE id = $id";

    if ($conn->query($sql) === TRUE) {
        header("Location: main.php?page=user");
        exit();
    } else {
        echo "Erreur lors de la suppression de l'utilisateur : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
} else {
    echo "ID de l'utilisateur non spécifié.";
}
?>
