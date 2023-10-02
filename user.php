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
<div class="container">
    <h1>Liste des Utilisateurs</h1>
    <table>
        <tr>
        <th>ID</th>
        <th>Nom</th>
        <th>Prénom</th>
        <th>Nom d'utilisateur</th>
        <th>Type d'utilisateur</th>
        <th>Email</th>
        <th>Actions</th>
        </tr>
        <?php
        // Connexion à la base de données (à adapter selon votre configuration)
        $conn = new mysqli("localhost", "caltus", "root", "signalement");

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
            echo "<td>" . $row["id"] . "</td>";
            echo "<td>" . $row["nom"] . "</td>";
            echo "<td>" . $row["prenom"] . "</td>";
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
    function updateUserType(selectElement, userId) {
        const newType = selectElement.value;

        // Envoyer une requête AJAX pour mettre à jour le type d'utilisateur dans la base de données
        const xhr = new XMLHttpRequest();
        xhr.open('POST', 'update_user_type.php', true);
        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                // Gérer la réponse de la requête AJAX si nécessaire
                console.log(xhr.responseText);
            }
        };
        xhr.send(`userId=${userId}&newType=${newType}`);
    }
</script>
