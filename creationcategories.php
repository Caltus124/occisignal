<?php
var_dump($_POST);
// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $titre = $_POST["titre"];
    $description = $_POST["description"];

    // Connexion à la base de données (à adapter selon votre configuration)
    $conn = new mysqli("localhost", "caltus", "root", "signalement");

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Préparez la requête d'insertion SQL
    $sql = "INSERT INTO categories (titre, description) VALUES (?, ?)";

    // Préparez la déclaration SQL en utilisant une requête préparée
    if ($stmt = $conn->prepare($sql)) {
        // Liez les paramètres à la requête
        $stmt->bind_param("ss", $titre, $description);

        // Exécutez la requête
        if ($stmt->execute()) {
            header("Location: main.php?page=creationcategories");
            exit();
        } else {
            echo "Erreur lors de l'ajout de la catégorie : " . $stmt->error;
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

<style>

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    margin: 0;
    padding: 0;
    padding-top: 50px;
}

.container {
    max-width: 1200px;
    margin: 20px auto;
    padding: 20px;
    background-color: #fff;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

h1 {
    font-size: 24px;
    color: #333;
    margin-bottom: 20px;
}

label {
    font-weight: bold;
    display: block;
    margin-bottom: 5px;
}

/* Style pour le champ titre */
#titre {
    outline: none;
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

/* Style pour le champ description */
#description {
    outline: none;
    width: 100%;
    padding: 8px;
    font-size: 16px;
    border: 1px solid #ddd;
    border-radius: 4px;
}

input[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    padding: 10px 15px;
    border: none;
    border-radius: 5px;
    font-size: 18px;
    cursor: pointer;
}

input[type="submit"]:hover {
    background-color: #0056b3;
}
.actions {
    display: flex;
    gap: 10px;
}

.actions a {
    text-decoration: none;
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.modifier-btn {
    background-color: #28a745;
    color: #fff;
}

.modifier-btn:hover {
    background-color: #1e7e34;
}

.supprimer-btn {
    background-color: #dc3545;
    color: #fff;
}

.supprimer-btn:hover {
    background-color: #c82333;
}
select {
    width: 100%; /* Largeur du select */
    padding: 8px; /* Espacement interne */
    font-size: 16px; /* Taille de la police */
    border: 1px solid #ddd; /* Bordure */
    border-radius: 4px; /* Coins arrondis */
    background-color: #fff; /* Couleur de fond */
    color: #333; /* Couleur du texte */
    cursor: pointer; /* Curseur de la souris */
}

/* Style des options du select */
select option {
    background-color: #fff; /* Couleur de fond */
    color: #333; /* Couleur du texte */
    font-size: 16px; /* Taille de la police */
}
    
</style>
<body>
<div class="container">
    <h1>Ajouter une Catégorie</h1>
    <form method="POST" action="creationcategories.php">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" required><br><br>
        
        <label for="description">Description :</label>
        <textarea name="description" id="description" rows="4" required></textarea><br><br>
        
        <input type="submit" value="Ajouter la Catégorie">
    </form>
</div>
</body>
</html>
