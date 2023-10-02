<?php
session_start();
require_once('bdd.php');

// Récupérer les données du formulaire de connexion
$username = $_POST['username'];
$password = hash('sha256', $_POST['password']); // Hachage SHA-256 du mot de passe

// Utiliser une requête préparée pour vérifier les informations de connexion
$stmt = $conn->prepare("SELECT id FROM user WHERE nom_utilisateur=? AND mot_de_passe=?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

// Vérifier si l'utilisateur existe dans la base de données
if ($result->num_rows == 1) {
    // Authentification réussie, récupérer l'ID de l'utilisateur
    $row = $result->fetch_assoc();
    $utilisateur_id = $row['id'];

    // Créer la session nom_utilisateur et utilisateur_id
    $_SESSION['nom_utilisateur'] = $username;
    $_SESSION['utilisateur_id'] = $utilisateur_id;

    // Rediriger l'utilisateur vers une page après la connexion
    header("Location: main.php?page=tickets");
    exit();
} else {
    // Identifiant ou mot de passe incorrect, rediriger vers la page de connexion avec un message d'erreur
    header("Location: index.php");
    exit();
}

// Fermer la connexion à la base de données
$stmt->close();
$conn->close();
