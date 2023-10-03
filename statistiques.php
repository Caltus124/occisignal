<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        /* Style pour le conteneur des cases */
        .container {
            max-width: 60%;
            margin: 20px auto;
            display: flex;
            justify-content: center;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }

        /* Style pour chaque case */
        .ticket-count {
            width: 150px; /* Ajustez la largeur selon vos préférences */
            text-align: center;
            background-color: #007BFF;
            color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.3);
            transition: transform 0.2s;
            cursor: pointer;
            margin-right: 20px; /* Espace entre chaque case */
        }

        /* Style pour la dernière case (pour éviter la marge à droite) */
        .ticket-count:last-child {
            margin-right: 0;
        }

        .ticket-count:hover {
            transform: scale(1.05);
        }

        .ticket-count h3 {
            font-size: 20px;
            margin: 0;
        }

        .ticket-count p {
            font-size: 36px;
            font-weight: bold;
            margin-top: 10px;
        }
        .ticket-count-basse {
            background-color: #4CAF50; /* Vert pour la gravité basse */
            color: #fff;
        }

        .ticket-count-normale {
            background-color: #FFC107; /* Jaune pour la gravité normale */
            color: #000;
        }

        .ticket-count-haute {
            background-color: #FF5722; /* Orange pour la gravité haute */
            color: #fff;
        }

        .ticket-count-urgente {
            background-color: #F44336; /* Rouge pour la gravité urgente */
            color: #fff;
        }

        /* Style pour le conteneur des graphiques */
        .chart-container {
            width: 60%; /* Largeur souhaitée */
            margin-left: auto;
            margin-right: auto;
            padding-top: 60px;
        }
    </style>
    <title>Compteur de Tickets</title>
</head>
<body>
    <?php
    // Connexion à la base de données (à adapter selon votre configuration)
    $conn = new mysqli("localhost", "caltus", "root", "signalement");

    // Vérification de la connexion
    if ($conn->connect_error) {
        die("La connexion à la base de données a échoué : " . $conn->connect_error);
    }

    // Requête SQL pour compter les tickets par priorité
    $sql = "SELECT priorite, COUNT(*) AS count FROM tickets GROUP BY priorite";
    $result = $conn->query($sql);

    // Tableau associatif pour stocker le nombre de tickets par priorité
    $ticketCounts = array(
        "Basse" => 0,
        "Normale" => 0,
        "Haute" => 0,
        "Urgente" => 0
    );

    // Remplir le tableau avec les résultats de la requête
    while ($row = $result->fetch_assoc()) {
        $priorite = $row["priorite"];
        $count = $row["count"];
        $ticketCounts[$priorite] = $count;
    }

    // Fermeture de la connexion à la base de données
    $conn->close();
    ?>

    <!-- Conteneur des cases de comptage des tickets -->
    <div class="container">
        <?php
        // Affichage des cases de comptage des tickets
        foreach ($ticketCounts as $priorite => $count) {
            // Déterminez la classe CSS en fonction de la gravité
            $classeCSS = "ticket-count ticket-count-" . strtolower($priorite);

            echo '<div class="' . $classeCSS . '">';
            echo '<h3>' . $priorite . '</h3>';
            echo '<p>' . $count . '</p>';
            echo '</div>';
        }
        ?>
    </div>
    
    <!-- Conteneur du graphique -->
    <div class="chart-container">
        <canvas id="ticketChart"></canvas>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
    // Récupération des données de gravité depuis PHP (assurez-vous que ces données sont disponibles dans votre script PHP)
    var gravites = <?php echo json_encode(array_keys($ticketCounts)); ?>;
    var compteurs = <?php echo json_encode(array_values($ticketCounts)); ?>;

    // Obtenez l'élément canvas
    var ctx = document.getElementById('ticketChart').getContext('2d');

    // Créez le graphique
    var myChart = new Chart(ctx, {
        type: 'bar', // Type de graphique (barre)
        data: {
            labels: gravites, // Labels de l'axe des X (graviété)
            datasets: [{
                label: 'Nombre de Tickets',
                data: compteurs, // Données (nombre de tickets)
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)', // Couleur pour la gravité "Basse"
                    'rgba(255, 205, 86, 0.2)', // Couleur pour la gravité "Normale"
                    'rgba(255, 99, 132, 0.2)', // Couleur pour la gravité "Haute"
                    'rgba(255, 0, 0, 0.2)'     // Couleur pour la gravité "Urgente"
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 205, 86, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 0, 0, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true,
                    precision: 0
                }
            }
        }
    });
</script>
</body>
</html>
