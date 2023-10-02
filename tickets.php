
<style>
    /* Styles CSS existants... */

    /* Ajoutez une classe pour cacher la section de résultats lorsque "Tous" est sélectionné */
    .hidden {
        display: none;
    }
    body {
        font-family: Arial, sans-serif;
        background-color: #f5f5f5;
        margin: 0;
        padding: 0;
        padding-top: 50px;
    }

    .container {
        max-width: 800px;
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

    select, input[type="submit"] {
        outline: none;
        width: 100%;
        padding: 8px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 4px;
        margin-bottom: 10px;
    }

    .ticket {
        max-width: 60%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }

    .ticket h1 {
        font-size: 24px;
        color: #333;
        margin-bottom: 20px;
    }

    .ticket p {
        font-size: 16px;
        color: #555;
        margin-bottom: 10px;
    }
    /* Style pour le message "Aucun ticket trouvé" */
    .no-tickets {
        max-width: 60%;
        margin: 20px auto;
        padding: 20px;
        background-color: #fff;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-size: 18px;
        color: #555;
        text-align: center;
    }

</style>

<div class="container">
    <h1>Filtrer les Tickets</h1>
    <form method="POST" action="">
        <label for="priorite">Priorité :</label>
        <select name="priorite" id="priorite">
            <option value="Tous">Tous</option> <!-- Option pour voir tous les tickets -->
            <option value="Basse">Basse</option>
            <option value="Normale">Normale</option>
            <option value="Haute">Haute</option>
            <option value="Urgente">Urgente</option>
        </select>

        <label for="statut">Statut :</label>
        <select name="statut" id="statut">
            <option value="Tous">Tous</option> <!-- Option pour voir tous les tickets -->
            <option value="Nouveau">Nouveau</option>
            <option value="Assigné">Assigné</option>
            <option value="En cours">En cours</option>
            <option value="Fermé">Fermé</option>
        </select>

        <input type="submit" value="Filtrer">
    </form>
</div>

<?php
    // Connexion à la base de données (à adapter selon votre configuration)
    $conn = new mysqli("localhost", "caltus", "root", "signalement");

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Construire la requête SQL pour sélectionner tous les tickets
    $sql = "SELECT * FROM tickets";

    // Vérifiez si le formulaire a été soumis
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupération des critères de filtrage
        $priorite = $_POST["priorite"];
        $statut = $_POST["statut"];

        // Construire la requête SQL en fonction des critères sélectionnés
        $sql .= " WHERE 1=1";

        if ($priorite !== "Tous") {
            $sql .= " AND priorite = '$priorite'";
        }

        if ($statut !== "Tous") {
            $sql .= " AND statut = '$statut'";
        }
    }

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Affichage des tickets filtrés ou non filtrés
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo '<div class="ticket">';
            echo '<h1>' . $row["titre"] . '</h1>';
            echo '<p>' . $row["description"] . '</p>';
            echo '<p>Priorité : ' . $row["priorite"] . '</p>';
            echo '<p>Statut : ' . $row["statut"] . '</p>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-tickets">Aucun ticket trouvé pour les critères sélectionnés.</div>';
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
    ?>
