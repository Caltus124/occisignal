<?php
// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez l'identifiant de la catégorie à supprimer depuis le formulaire
    $categorie_id = $_POST["categorie_id"];

    // Vérifiez si l'identifiant de la catégorie est valide (par exemple, s'assurer qu'il existe en base de données et qu'il peut être supprimé)

    // Connexion à la base de données (à adapter selon votre configuration)
    $conn = new mysqli("localhost", "caltus", "root", "signalement");

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

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
