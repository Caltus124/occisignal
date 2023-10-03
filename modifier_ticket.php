<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Récupérez les données soumises depuis le formulaire de modification
    $nouveauTitre = $_POST['nouveau-titre'];
    $nouvelleDescription = $_POST['nouvelle-description'];
    $nouvellePriorite = $_POST['nouvelle-priorite'];
    $nouveauStatut = $_POST['nouveau-statut'];
    $ticketId = $_POST['ticket-id'];
    $assignationId = $_POST['assignation'];
    $categorieId = $_POST['categorie'];

    echo $categorieId;

    // Vérifiez si l'assignation est "0" (Non assigné) et ajustez la valeur en conséquence
    if ($assignationId === '0') {
        $assignationValue = null; // La valeur NULL indique "Non assigné" dans la base de données
    } else {
        $assignationValue = $assignationId; // Utilisez la valeur sélectionnée
    }

    try {
        // Construisez la requête SQL UPDATE pour mettre à jour les données du ticket
        $sql = "UPDATE tickets SET
            titre = ?,
            description = ?,
            priorite = ?,
            statut = ?,
            assignation = ?,
            categorie_id = ?,
            date_modification = CURRENT_TIMESTAMP
        WHERE id = ?";

        // Préparez la requête SQL
        $stmt = $conn->prepare($sql);

        if (!$stmt) {
            throw new Exception("Erreur de préparation de la requête : " . $conn->error);
        }

        // Liez les paramètres
        if (!$stmt->bind_param("ssssssi", $nouveauTitre, $nouvelleDescription, $nouvellePriorite, $nouveauStatut, $assignationId, $categorieId, $ticketId)) {
            throw new Exception("Erreur de liaison des paramètres : " . $stmt->error);
        }

        // Exécutez la requête
        if (!$stmt->execute()) {
            throw new Exception("Erreur lors de l'exécution de la requête : " . $stmt->error);
        } else {
            header("Location: main.php?page=tickets&id=". $ticketId);
            exit();
        }

        // Fermeture de la connexion à la base de données
        $stmt->close();
        $conn->close();
    } catch (Exception $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
