<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}

// Démarrer la session si elle n'a pas déjà été démarrée
session_start();

// Vérifier si l'utilisateur est connecté (vous devez implémenter la gestion de l'authentification)
if (!isset($_SESSION["utilisateur_id"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: login.php"); // Assurez-vous de créer une page de connexion appropriée
    exit;
}

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupération des données du formulaire
    $titre = $_POST["titre"];
    $description = $_POST["description"];
    $priorite = $_POST["priorite"];
    $statut = $_POST["statut"];
    $categorie_id = $_POST["categorie"];
    $utilisateur_id = $_SESSION["utilisateur_id"]; // Récupérer l'ID de l'utilisateur connecté depuis la session

    // Préparez la requête d'insertion SQL
    $sql = "INSERT INTO tickets (titre, description, priorite, statut, utilisateur_id, categorie_id) VALUES (?, ?, ?, ?, ?, ?)";

    // Préparez la déclaration SQL en utilisant une requête préparée
    if ($stmt = $conn->prepare($sql)) {
        // Liez les paramètres à la requête
        $stmt->bind_param("ssssii", $titre, $description, $priorite, $statut, $utilisateur_id, $categorie_id);

        // Exécutez la requête
        if ($stmt->execute()) {
            header("Location: main.php?page=creationtickets");
            exit();
        } else {
            echo "Erreur lors de la création du ticket : " . $stmt->error;
        }

        // Fermez la déclaration
        $stmt->close();
    } else {
        echo "Erreur de préparation de la requête : " . $conn->error;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
}
?>
