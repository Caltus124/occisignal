
<h1>Détails du Ticket</h1>

<?php
// Récupérer l'ID du ticket depuis la requête GET
if (isset($_GET['id'])) {
    $ticketId = $_GET['id'];

    // Connexion à la base de données (à adapter selon votre configuration)
    $conn = new mysqli("localhost", "caltus", "root", "signalement");

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Construire la requête SQL pour sélectionner les informations du ticket en fonction de l'ID
    $sql = "SELECT t.*, u.nom AS nom_utilisateur, c.id AS categorie_id, c.titre AS categorie_titre
    FROM tickets t
    LEFT JOIN user u ON t.utilisateur_id = u.id
    LEFT JOIN categories c ON t.categorie_id = c.id
    WHERE t.id = $ticketId
    ";
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Vérifier si le ticket existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo '<div class="container">';
        echo '<h2>' . $row["titre"] . '</h2>';
        echo '<p><strong>Description :</strong> ' . $row["description"] . '</p>';
        echo '<p><strong>Priorité :</strong> ' . $row["priorite"] . '</p>';
        echo '<p><strong>Statut :</strong> ' . $row["statut"] . '</p>';
        echo '<p><strong>Date de création :</strong> ' . $row["date_creation"] . '</p>';
        echo '<p><strong>Date de modification :</strong> ' . $row["date_modification"] . '</p>';
        echo '<p><strong>Nom de l\'utilisateur :</strong> ' . $row["nom_utilisateur"] . '</p>';
        echo '<p><strong>ID Catégorie :</strong> ' . $row["categorie_titre"] . '</p>';
        // Vous pouvez afficher d'autres informations du ticket ici si nécessaire
        echo '</div>';
    } else {
        echo '<div class="container">';
        echo '<p>Le ticket avec l\'ID ' . $ticketId . ' n\'existe pas.</p>';
        echo '</div>';
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
} else {
    echo '<p>Aucun ID de ticket spécifié.</p>';
}
?>
<div class="centered-container">
    <!-- Bouton de retour en arrière -->
    <a class="back-button" href="javascript:history.back()">Retour en arrière</a>
</div>
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
    }

    h1, h2 {
        color: #333;
        text-align: center;
        margin-top: 20px;
    }

    p {
        font-size: 16px;
        color: #555;
        margin: 10px;
    }

    strong {
        font-weight: bold;
    }

    .container {
        max-width: 60%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
    .back-button {
        display: inline-block;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007BFF;
        color: #fff;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
        margin-left: auto;
        margin-right: auto;
    }

    .back-button:hover {
        background-color: #0056b3;
    }
    .centered-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 50px; /* Cela assure que le conteneur occupe toute la hauteur de la fenêtre */
    }
</style>


