<?php
session_start();

// Configuration de la base de données
$hostname = "127.0.0.1"; // Adresse du serveur MySQL
$username = "caltus"; // Nom d'utilisateur MySQL
$password = "root"; // Mot de passe MySQL
$database = "signalement"; // Nom de la base de données

// Créer une connexion à la base de données
$conn = new mysqli($hostname, $username, $password, $database);

// Vérifier la connexion à la base de données
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}
?>