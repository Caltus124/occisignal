<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}

// Vérifiez si l'ID du ticket à supprimer a été transmis via GET
if (isset($_GET['id'])) {
    // Récupérez l'ID du ticket à supprimer depuis l'URL
    $ticketId = $_GET['id'];

    // Requête SQL pour supprimer le ticket en fonction de son ID
    $sql = "DELETE FROM tickets WHERE id = ?";

    $stmt = $conn->prepare($sql);
    
    // Lier l'ID du ticket au paramètre de la requête
    $stmt->bind_param("i", $ticketId);

    if ($stmt->execute()) {
        header("Location: main.php?page=tickets");
        exit();
    } else {
        // La suppression a échoué
        echo "La suppression du ticket a échoué : " . $stmt->error;
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    // Si l'ID du ticket n'a pas été transmis, affichez un message d'erreur
    echo "ID de ticket manquant.";
}
?>
