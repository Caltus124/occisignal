<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}
?>
<h1>Détails du Ticket</h1>
<?php
// Récupérer l'ID du ticket depuis la requête GET
if (isset($_GET['id'])) {
    $ticketId = $_GET['id'];

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

    $sql2 = "SELECT id, nom_utilisateur FROM user WHERE type_utilisateur = 'admin' OR type_utilisateur = 'moderateur'";
    $result2 = $conn->query($sql2);

    // Créez un tableau pour stocker les administrateurs et les modérateurs
    $adminsAndModerators = array();

    if ($result2->num_rows > 0) {
        while ($row = $result2->fetch_assoc()) {
            // Ajoutez les administrateurs et les modérateurs au tableau
            $adminsAndModerators[$row["id"]] = $row["nom_utilisateur"];
        }
    }

    // Vérifier si le ticket existe
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

?>
<div class="container">
    <h2><?php echo $row["titre"]; ?></h2>
    <p><strong>Description :</strong> <?php echo $row["description"]; ?></p>
    <p><strong>Priorité :</strong> <?php echo $row["priorite"]; ?></p>
    <p><strong>Statut :</strong> <?php echo $row["statut"]; ?></p>
    <p><strong>Date de création :</strong> <?php echo $row["date_creation"]; ?></p>
    <p><strong>Date de modification :</strong> <?php echo $row["date_modification"]; ?></p>
    <p><strong>Nom de l'utilisateur :</strong> <?php echo $row["nom_utilisateur"]; ?></p>
    <p><strong>ID Catégorie :</strong> <?php echo $row["categorie_titre"]; ?></p>
    <p><strong>Assignation :</strong>
    <?php
    if ($row["assignation"] != 0) {
        $assignationId = $row["assignation"];

        // Construisez une requête SQL pour récupérer le nom de l'utilisateur en fonction de l'ID d'assignation
        $sqlAssignation = "SELECT nom_utilisateur FROM user WHERE id = $assignationId";
        $resultAssignation = $conn->query($sqlAssignation);

        if ($resultAssignation->num_rows > 0) {
            $rowAssignation = $resultAssignation->fetch_assoc();
            echo $rowAssignation["nom_utilisateur"];
        } else {
            echo "Utilisateur non trouvé";
        }
    } else {
        echo "Non assigné";
    }
    ?>
    </p>

    <div class="image-container">
        <?php
        if (!empty($row["image_data"])) {
            echo '<img src="data:image/jpeg;base64,' . $row["image_data"] . '" alt="Image du ticket" class="ticket-image">';
        }
        ?>
    </div>
    <p><strong>Longitude :</strong> <?php echo $row["longitude"]; ?></p>
    <p><strong>Latitude :</strong> <?php echo $row["latitude"]; ?></p>



    <!-- Lien pour modifier le ticket -->
    <a class="edit-link" href="#" onclick="showEditForm()">Modifier ce ticket</a>

    <!-- Formulaire de modification (initiallement caché) -->
    <form id="edit-form" style="display: none;" action="modifier_ticket.php" method="post">
        <input type="hidden" name="ticket-id" value="<?php echo $ticketId; ?>"> 
        <label for="nouveau-titre">Nouveau Titre :</label>
        <input type="text" id="nouveau-titre" name="nouveau-titre" value="<?php echo $row["titre"]; ?>"><br>

        <label for="nouvelle-description">Nouvelle Description :</label>
        <textarea id="nouvelle-description" name="nouvelle-description"><?php echo $row["description"]; ?></textarea><br>

        <label for="nouvelle-priorite">Nouvelle Priorité :</label>
        <select id="nouvelle-priorite" name="nouvelle-priorite">
            <option value="Basse" <?php echo ($row["priorite"] == "Basse") ? "selected" : ""; ?>>Basse</option>
            <option value="Normale" <?php echo ($row["priorite"] == "Normale") ? "selected" : ""; ?>>Normale</option>
            <option value="Haute" <?php echo ($row["priorite"] == "Haute") ? "selected" : ""; ?>>Haute</option>
            <option value="Urgente" <?php echo ($row["priorite"] == "Urgente") ? "selected" : ""; ?>>Urgente</option>
        </select><br>

        <label for="nouveau-statut">Nouveau Statut :</label>
        <select id="nouveau-statut" name="nouveau-statut">
            <option value="Nouveau" <?php echo ($row["statut"] == "Nouveau") ? "selected" : ""; ?>>Nouveau</option>
            <option value="Assigné" <?php echo ($row["statut"] == "Assigné") ? "selected" : ""; ?>>Assigné</option>
            <option value="En cours" <?php echo ($row["statut"] == "En cours") ? "selected" : ""; ?>>En cours</option>
            <option value="Fermé" <?php echo ($row["statut"] == "Fermé") ? "selected" : ""; ?>>Fermé</option>
        </select><br>

        <label for="categorie">Catégorie :</label>
        <select id="categorie" name="categorie">
            <?php

            $sql4 = "SELECT * FROM categories";
    
            $result4 = $conn->query($sql4);

            if ($result4->num_rows > 0) {
                while ($row = $result4->fetch_assoc()) {
                    echo '<option value="' . $row["id"] . '">' . $row["titre"] . '</option>';
                }
            }
            ?>
        </select>

        
        <label for="assignation">Assigner à :</label>
        <select id="assignation" name="assignation">
            <option value="0">Non assigné</option>
            <?php
            // Générez les options pour les administrateurs et les modérateurs avec leurs noms au lieu de leurs ID
            foreach ($adminsAndModerators as $userId => $userName) {
                echo '<option value="' . $userId . '"';
                
                // Si l'ID d'assignation correspond à l'ID actuel de l'utilisateur, sélectionnez cette option
                if ($row["assignation"] == $userId) {
                    echo ' selected';
                }

                echo '>' . $userName . '</option>';
            }
            ?>
        </select><br><br>



        <button type="submit">Enregistrer</button>
        <button type="button" onclick="hideEditForm()">Annuler</button>
        <a class="delete-link" href="supprimer_ticket.php?id=<?php echo $ticketId; ?>">Supprimer ce ticket</a>
    </form>

</div>

<script>
    // Fonction pour afficher le formulaire de modification
    function showEditForm() {
        document.getElementById("edit-form").style.display = "block";
    }

    // Fonction pour masquer le formulaire de modification
    function hideEditForm() {
        document.getElementById("edit-form").style.display = "none";
    }
</script>

<?php
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
    <a class="back-button" href="main.php?page=tickets">Retour en arrière</a>

</div>

<style>
    .image-container {
        /* Ajoutez vos styles CSS ici */
        border: 2px solid #ccc; /* Exemple : ajoute une bordure grise de 2 pixels */
        padding: 10px; /* Exemple : ajoute une marge intérieure de 10 pixels */
        text-align: center; /* Exemple : centre l'image horizontalement */
    }

    .ticket-image {
        /* Ajoutez des styles supplémentaires à l'image elle-même ici si nécessaire */
        max-width: 100%; /* Exemple : assure que l'image ne dépasse pas la largeur de son conteneur */
        height: auto; /* Garde les proportions de l'image */
    }
    .delete-link {
        background-color: #dc3545; /* Rouge */
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
        margin-left: 10px;
    }

    .delete-link:hover {
        background-color: #c82333; /* Rouge plus foncé au survol */
    }
    .edit-link {
        display: inline-block;
        margin-top: 20px;
        background-color: #007BFF;
        color: #fff;
        padding: 10px 20px;
        text-decoration: none;
        border-radius: 4px;
        transition: background-color 0.3s;
    }

    .edit-link:hover {
        background-color: #0056b3;
    }
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
    }    .image-container {
        /* Ajoutez vos styles CSS ici */
        border: 2px solid #ccc; /* Exemple : ajoute une bordure grise de 2 pixels */
        padding: 10px; /* Exemple : ajoute une marge intérieure de 10 pixels */
        text-align: center; /* Exemple : centre l'image horizontalement */
    }

    .ticket-image {
        /* Ajoutez des styles supplémentaires à l'image elle-même ici si nécessaire */
        max-width: 100%; /* Exemple : assure que l'image ne dépasse pas la largeur de son conteneur */
        height: auto; /* Garde les proportions de l'image */
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
    /* Styles CSS pour le formulaire de modification */

    #edit-form label {
        font-weight: bold;
        display: block;
        margin-bottom: 5px;
    }

    #edit-form {
        margin-top: 20px;
    }

    #edit-form input[type="text"],
    #edit-form textarea,
    #edit-form select {
        width: 100%;
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    #edit-form select {
        padding: 8px;
    }

    #edit-form button[type="submit"],
    #edit-form button[type="button"] {
        outline: none;
        padding: 10px 20px;
        font-size: 16px;
        background-color: #007BFF;
        color: #fff;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #edit-form button[type="submit"] {
        margin-right: 10px;
    }

    #edit-form button[type="submit"]:hover,
    #edit-form button[type="button"]:hover {
        background-color: #0056b3;
    }

</style>
