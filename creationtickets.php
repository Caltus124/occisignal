<style>
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

        input[type="text"], textarea, select {
            outline: none;
            width: 100%;
            padding: 8px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            margin-bottom: 10px;
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
        #categorie{
            margin-top: 5px;
        }
    </style>
    <div class="container">
        <h1>Créer un Ticket</h1>
        <form method="POST" action="traitement_ticket.php">
        <label for="titre">Titre :</label>
        <input type="text" name="titre" id="titre" required><br><br>

        <label for="description">Description :</label>
        <textarea name="description" id="description" rows="4" required></textarea><br><br>

        <label for="priorite">Priorité :</label>
        <select name="priorite" id="priorite">
            <option value="Basse">Basse</option>
            <option value="Normale">Normale</option>
            <option value="Haute">Haute</option>
            <option value="Urgente">Urgente</option>
        </select><br><br>

        <label for="statut">Statut :</label>
            <select name="statut" id="statut">
                <option value="Nouveau">Nouveau</option>
                <option value="Assigné">Assigné</option>
                <option value="En cours">En cours</option>
                <option value="Fermé">Fermé</option>
            </select><br>

        <!-- Menu déroulant des catégories -->
        <label for="categorie" style="margin-top: 20px;">Catégorie :</label>
        <select name="categorie" id="categorie">
            <?php
            // Connexion à la base de données (à adapter selon votre configuration)
            $conn = new mysqli("localhost", "caltus", "root", "signalement");

            // Vérification de la connexion
            if ($conn->connect_error) {
                die("La connexion à la base de données a échoué : " . $conn->connect_error);
            }

            // Requête SQL pour récupérer les catégories
            $sql = "SELECT id, titre FROM categories"; // Supposons que la table des catégories a des colonnes 'id' et 'titre'
            $result = $conn->query($sql);

            // Vérification des résultats et affichage des catégories
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<option value='" . $row["id"] . "'>" . $row["titre"] . "</option>";
                }
            } else {
                echo "<option value='0'>Aucune catégorie trouvée</option>";
            }

            // Fermeture de la connexion à la base de données
            $conn->close();
            ?>
        </select><br><br>

        <input type="submit" value="Créer le Ticket">
    </form>
    </div>