<?php
session_start();
require_once('bdd.php');

// Récupérer les données du formulaire de connexion
$username = $_POST['username'];
$password = hash('sha256', $_POST['password']); // Hachage SHA-256 du mot de passe

// Utiliser une requête préparée pour vérifier les informations de connexion
$stmt = $conn->prepare("SELECT id, type_utilisateur FROM user WHERE nom_utilisateur=? AND mot_de_passe=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'utilisateur existe dans la base de données et s'il a le rôle d'administrateur
if ($result->num_rows == 1) {
    $row = $result->fetch_assoc();
    $utilisateur_id = $row['id'];
    $type_utilisateur = $row['type_utilisateur'];

    // Vérifier si l'utilisateur a le rôle d'administrateur
    if ($type_utilisateur === 'admin' || $type_utilisateur === 'modérateur') {
        // Mettre à jour le statut de l'utilisateur dans la base de données
        $updateStmt = $conn->prepare("UPDATE user SET statut='en ligne' WHERE id=?");
        $updateStmt->bind_param("i", $utilisateur_id); // "i" indique que c'est un entier
        $updateStmt->execute();
        $updateStmt->close();

        // Authentification réussie pour un administrateur, créer la session
        $_SESSION['nom_utilisateur'] = $username;
        $_SESSION['utilisateur_id'] = $utilisateur_id;
        $_SESSION['type_utilisateur'] = $type_utilisateur;

        // Rediriger l'administrateur vers une page après la connexion
        header("Location: main.php?page=tickets");
        exit();
    } else {
        // L'utilisateur n'a pas le rôle d'administrateur, rediriger vers la page de connexion avec un message d'erreur
        header("Location: index.php?error=1");
        exit();
    }
} else {
    // Identifiant ou mot de passe incorrect, rediriger vers la page de connexion avec un message d'erreur
    header("Location: index.php?error=2");
    exit();
}

// Fermer la connexion à la base de données
$stmt->close();
$conn->close();
?>
