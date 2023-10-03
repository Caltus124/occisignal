<?php
session_start();
require_once('bdd.php');
// Assurez-vous que l'utilisateur est connecté
if (isset($_SESSION['utilisateur_id'])) {
    // Récupérez l'ID de l'utilisateur actuellement connecté
    $utilisateur_id = $_SESSION['utilisateur_id'];


    // Mettre à jour le statut de l'utilisateur dans la base de données
    $updateStmt = $conn->prepare("UPDATE user SET statut='hors ligne' WHERE id=?");
    $updateStmt->bind_param("i", $utilisateur_id); // "i" indique que c'est un entier
    $updateStmt->execute();
    $updateStmt->close();

    // Détruire la session
    session_destroy();
}

// Rediriger vers la page de connexion
header('Location: login.php');
?>
