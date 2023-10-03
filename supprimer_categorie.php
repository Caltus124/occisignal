<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez l'identifiant de la catégorie à supprimer depuis le formulaire
    $categorie_id = $_POST["categorie_id"];

    // Requête SQL pour supprimer la catégorie
    $sql = "DELETE FROM categories WHERE id = ?";

    // Préparez la déclaration SQL en utilisant une requête préparée
    if ($stmt = $conn->prepare($sql)) {
        // Liez l'identifiant de la catégorie à la requête
        $stmt->bind_param("i", $categorie_id);
    
        // Exécutez la requête
        if ($stmt->execute()) {
            // La catégorie a été supprimée avec succès
            $message = "La catégorie a été supprimée avec succès.";
        } else {
            // Erreur lors de la suppression de la catégorie
            $message = "Erreur lors de la suppression de la catégorie : " . $stmt->error;
        }
    
        // Fermez la déclaration
        $stmt->close();
    } else {
        $message = "Erreur de préparation de la requête : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();

    // Rediriger l'utilisateur vers la page précédente avec un message
    header("Location: main.php?page=creationcategories");
    exit();
}
?>
