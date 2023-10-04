
<?php
session_start();
require_once('bdd.php');
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}
?>

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
    max-width: 60%;
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
    cursor: pointer;
    transition: transform 0.3s;
}

.ticket:hover {
    transform: scale(1.02);
}

/* Style pour les balises de tag en fonction de la priorité */
.tag-basse {
    background-color: #28a745; /* Vert pour priorité basse */
    color: #fff; /* Texte blanc pour une meilleure lisibilité */
    padding: 5px 10px;
    border-radius: 5px;
}

.tag-normale {
    background-color: #333; /* Couleur normale pour priorité normale */
    color: #fff; /* Texte blanc pour une meilleure lisibilité */
    padding: 5px 10px;
    border-radius: 5px;
}

.tag-haute {
    background-color: #ffc107; /* Jaune pour priorité haute */
    color: #000; /* Texte noir pour une meilleure lisibilité */
    padding: 5px 10px;
    border-radius: 5px;
}

.tag-urgente {
    background-color: #dc3545; /* Rouge pour priorité urgente */
    color: #fff; /* Texte blanc pour une meilleure lisibilité */
    padding: 5px 10px;
    border-radius: 5px;
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

/* Alignement des éléments dans ticket-details */
.ticket-details {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding-top: 20px;
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
<br>
<br>

<?php

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
    $sql .= " ORDER BY date_creation DESC";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    // Affichage des tickets filtrés ou non filtrés
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            // Déterminez la classe de tag en fonction de la priorité du ticket
            $tagClass = '';
            switch ($row["priorite"]) {
                case 'Basse':
                    $tagClass = 'tag-basse';
                    break;
                case 'Normale':
                    $tagClass = 'tag-normale';
                    break;
                case 'Haute':
                    $tagClass = 'tag-haute';
                    break;
                case 'Urgente':
                    $tagClass = 'tag-urgente';
                    break;
                default:
                    // Gérer d'autres cas si nécessaire
                    break;
            }
        
            // Affichage du ticket avec la balise de tag colorée en fonction de la priorité
            echo '<div class="ticket" data-id="' . $row["id"] . '">';
            echo '<h1>' . $row["titre"] . '</h1>';
            echo '<p>' . $row["description"] . '</p>';
            echo '<div class="ticket-details">';
            echo '<span class="tag ' . $tagClass . '">' . $row["priorite"] . '</span>';
            echo '<p class="ticket-date">' . $row["date_creation"] . '</p>';
            echo '<p class="ticket-statut tag-blue">' . $row["statut"] . '</p>';
            echo '</div>';
            echo '</div>';
        }
    } else {
        echo '<div class="no-tickets">Aucun ticket trouvé pour les critères sélectionnés.</div>';
    }

    // Fermeture de la connexion à la base de données
    $stmt->close();
    $conn->close();
    ?>

<script>
    // Ajoutez cet événement de clic à toutes les div de ticket
    const ticketDivs = document.querySelectorAll('.ticket');
    ticketDivs.forEach(ticketDiv => {
        ticketDiv.addEventListener('click', function() {
            // Récupérez l'ID du ticket à partir de l'attribut data-id
            const ticketId = this.getAttribute('data-id');
            
            // Redirigez l'utilisateur vers la page de détails du ticket en fonction de l'ID
            window.location.href = 'main.php?page=tickets&id=' + ticketId;
        });
    });
</script>
<script src="ticket.js"></script>