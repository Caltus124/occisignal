<?php
session_start();
require_once('bdd.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $nom_utilisateur = $_POST['nom_utilisateur'];
    $email = $_POST['email'];
    $mot_de_passe = hash('sha256', $_POST['mot_de_passe']); // Encodage en SHA-256
    $confirmer_mot_de_passe = $_POST['confirmer_mot_de_passe'];

    // Valider le mot de passe et la confirmation du mot de passe
    if ($mot_de_passe !== hash('sha256', $confirmer_mot_de_passe)) {
        echo "Les mots de passe ne correspondent pas.";
    } else {
        // Valider le formulaire et insérer l'utilisateur dans la base de données
        // Assurez-vous d'utiliser des requêtes préparées pour la sécurité
        $insert_query = "INSERT INTO user (nom, prenom, nom_utilisateur, email, mot_de_passe, type_utilisateur)
                         VALUES (?, ?, ?, ?, ?, 'standard')";
        $stmt = $conn->prepare($insert_query);
        $stmt->bind_param("sssss", $nom, $prenom, $nom_utilisateur, $email, $mot_de_passe);

        if ($stmt->execute()) {
            // L'inscription a réussi, redirigez l'utilisateur vers une page de connexion
            header("Location: login.php?registration=success");
            exit();
        } else {
            echo "Erreur lors de l'inscription : " . $stmt->error;
        }
        
    }
}

// Fermer la connexion à la base de données
$conn->close();
?>
