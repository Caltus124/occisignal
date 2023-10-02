<?php
// Connexion à la base de données (à adapter selon votre configuration)
$conn = new mysqli("localhost", "caltus", "root", "signalement");

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
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
