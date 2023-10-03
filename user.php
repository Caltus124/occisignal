<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $nom = $_POST["nom"];
    $prenom = $_POST["prenom"];
    $nom_utilisateur = $_POST["nom_utilisateur"];
    $email = $_POST["email"];
    $mot_de_passe = $_POST["mot_de_passe"];
    $type_utilisateur = $_POST["type_utilisateur"]; // Vous devez avoir un champ "type_utilisateur" dans votre base de données

    // Connexion à la base de données (à adapter selon votre configuration)
    $conn = new mysqli("localhost", "caltus", "root", "signalement", "3306");

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Préparez la requête d'insertion SQL
    $sql = "INSERT INTO user (nom, prenom, nom_utilisateur, email, mot_de_passe, type_utilisateur) VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $conn->prepare($sql)) {
        // Hachez le mot de passe en utilisant SHA-256
        $mot_de_passe_hache = hash('sha256', $mot_de_passe); // Hachage en SHA-256
    
        // Liez les paramètres à la requête
        $stmt->bind_param("ssssss", $nom, $prenom, $nom_utilisateur, $email, $mot_de_passe_hache, $type_utilisateur);
    
        // Exécutez la requête
        if ($stmt->execute()) {
            $message = "L'utilisateur a été ajouté avec succès.";
        } else {
            $message = "Erreur lors de l'ajout de l'utilisateur : " . $stmt->error;
        }
    
        // Fermez la déclaration
        $stmt->close();
    } else {
        $message = "Erreur de préparation de la requête : " . $conn->error;
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
        max-width: 60%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        margin-bottom: 70px;
    }

    h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    table {
        margin-top: 30px;
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px 15px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: #f2f2f2;
        font-weight: bold;
    }

    tr:nth-child(even) {
        background-color: #f5f5f5;
    }

    tr:hover {
        background-color: #ddd;
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
        margin: 15px;
        padding: 5px;
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

    .create-user-form h2 {
        font-size: 20px;
        color: #333;
        margin-bottom: 20px;
    }

    .create-user-form label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    .create-user-form input[type="text"],
    .create-user-form input[type="email"],
    .create-user-form input[type="password"],
    .create-user-form select {
        outline: none;
        width: 100%;
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .create-user-form input[type="submit"] {
        background-color: #007BFF;
        color: #fff;
        padding: 10px 15px;
        border: none;
        border-radius: 5px;
        font-size: 18px;
        cursor: pointer;
    }

    .create-user-form input[type="submit"]:hover {
        background-color: #0056b3;
    }
    .status-dot {
        width: 10px;
        height: 10px;
        border-radius: 50%;
        display: inline-block;
        margin-right: 5px;
    }

    .green {
        background-color: green;
    }

    .red {
        background-color: red;
    }
</style>

<div class="container">
<div class="create-user-form">
        <h2>Créer un Nouvel Utilisateur</h2>
        <form method="POST" action="">
            <label for="nom">Nom :</label>
            <input type="text" name="nom" id="nom" required><br><br>
            
            <label for="prenom">Prénom :</label>
            <input type="text" name="prenom" id="prenom" required><br><br>
            
            <label for="nom_utilisateur">Nom d'utilisateur :</label>
            <input type="text" name="nom_utilisateur" id="nom_utilisateur" required><br><br>
            
            <label for="type_utilisateur">Type d'utilisateur :</label>
            <select name="type_utilisateur" id="type_utilisateur">
                <option value="admin">Admin</option>
                <option value="standard" selected>Standard</option>
                <option value="modérateur">Modérateur</option>
            </select><br><br>
            
            <label for="email">Email :</label>
            <input type="email" name="email" id="email" required><br><br>
            
            <label for="mot_de_passe">Mot de passe :</label>
            <input type="password" name="mot_de_passe" id="mot_de_passe" required><br><br>
            
            <input type="submit" value="Créer l'Utilisateur">
        </form>
    </div>
</div>

<div class="container">
    <h1>Liste des Utilisateurs</h1>
    <table>
        <tr>
        <th>Status</th>
        <th>Nom d'utilisateur</th>
        <th>Type d'utilisateur</th>
        <th>Email</th>
        <th>Actions</th>
        </tr>
        <?php
        // Connexion à la base de données (à adapter selon votre configuration)
        $conn = new mysqli("localhost", "caltus", "root", "signalement", "3306");

        // Vérification de la connexion
        if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
        }

        // Requête SQL pour sélectionner tous les utilisateurs
        $sql = "SELECT * FROM user";
        $result = $conn->query($sql);

        // Affichage des utilisateurs dans le tableau
        if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>";
            if ($row["statut"] === "en ligne") {
                echo "<span class='status-dot green'></span>"; // Point vert pour "en ligne"
            } else {
                echo "<span class='status-dot red'></span>"; // Point rouge pour "hors ligne"
            }
            echo "</td>";
            echo "<td>" . $row["nom_utilisateur"] . "</td>";
            echo "<td>";
            echo "<select name='type_utilisateur' onchange='updateUserType(this, " . $row['id'] . ")'>";
            echo "<option value='admin' " . ($row['type_utilisateur'] === 'admin' ? 'selected' : '') . ">Admin</option>";
            echo "<option value='standard' " . ($row['type_utilisateur'] === 'standard' ? 'selected' : '') . ">Standard</option>";
            echo "<option value='modérateur' " . ($row['type_utilisateur'] === 'modérateur' ? 'selected' : '') . ">Modérateur</option>";
            echo "</select>";
            echo "</td>";
            echo "<td>" . $row["email"] . "</td>";
            echo "<td class='actions'><a href='supprimeuser.php?id=" . $row["id"] . "' class='supprimer-btn'>Supprimer</a></td>";
            echo "</tr>";
        }
        } else {
        echo "<tr><td colspan='7'>Aucun utilisateur trouvé.</td></tr>";
        }

        // Fermeture de la connexion à la base de données
        $conn->close();
        ?>
    </table>
</div>
<script>
    function updateUserStatus(selectElement, userId) {
        const newStatus = selectElement.value;

        // Envoyer une requête AJAX pour mettre à jour le statut de l'utilisateur dans la base de données
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_user_status.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Gérer la réponse de la requête AJAX si nécessaire
                console.log(xhr.responseText);
            }
        };
        xhr.send(`userId=${userId}&newStatus=${newStatus}`);
    }
</script>
