<?php
session_start();
if (!isset($_SESSION['nom_utilisateur'])) {
    header('Location: login.php'); // Rediriger vers la page de connexion si l'utilisateur n'est pas connecté
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Admin panel</title>
</head>
<body>

    <!-- Barre de navigation en bas -->
    <footer>
        <nav>
            <ul>
                <li><a href="deconnexion.php"><img src="./images/deconnexion.png" alt="Deconnexion" width="40" height="40"></a></li>
                <li><a href="signalement.php"><img src="./images/signalement.png" alt="Signalement" width="40" height="40"></a></li>
                <li><a href="home"><img src="./images/home.png" alt="Home" width="40" height="40"></a></li>
            </ul>
        </nav>
    </footer>

    <style>
        body {
            padding: 0;
            margin: 0;
        }

        /* Styles pour la barre de navigation en bas */
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: #333;
            color: #fff;
            padding: 10px 0;
            text-align: center;
        }

        footer ul {
            list-style: none;
            padding: 0;
        }

        footer ul li {
            display: inline;
            margin-right: 20px;
        }

        footer ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 20px;
            font-family: sans-serif;
        }
    </style>
</body>
</html>
