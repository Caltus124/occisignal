<!DOCTYPE html>
<html>
<head>
    <title>OcciSignal | Connexion</title>
</head>
<body>
    <form action="./login.php" method="post">
        <label for="username">Nom d'utilisateur :</label>
        <input type="text" id="username" name="username" required autocomplete="off"><br>

        <label for="password">Mot de passe :</label>
        <input type="password" id="password" name="password" required><br>

        <input type="submit" value="Connexion">
        <?php
        // Vérifier si le paramètre error est défini et égal à 2 (erreur d'identifiant ou de mot de passe incorrect)
        if (isset($_GET['error']) && $_GET['error'] == 1) {
            echo '<p style="color: red;">Vous vous n\'êtes pas administrateur</p>';
        }
        if (isset($_GET['error']) && $_GET['error'] == 2) {
            echo '<p style="color: red;">Identifiant ou mot de passe incorrect</p>';
        }
        ?>
    </form>
</body>
<style>
@import url("https://fonts.googleapis.com/css2?family=Poppins:wght@200;300;400;500;600;700&display=swap");
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    font-family: "Poppins", sans-serif;
}

/* Style de base pour le corps de la page */
body {
    font-family: Arial, sans-serif;
    background-color: #eef5fe;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
    margin: 0;
}

/* Style du formulaire */
form {
    background-color: #fff;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    width: 300px;
}

/* Style des libellés et des champs de saisie */
label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

input[type="text"],
input[type="password"] {
    outline: none;
    width: 100%;
    padding: 10px;
    margin-bottom: 20px;
    border: 1px solid #ccc;
    border-radius: 3px;
    font-size: 16px;
}


/* Style du bouton de soumission */
input[type="submit"] {
    background-color: #007BFF;
    color: #fff;
    border: none;
    padding: 10px 20px;
    border-radius: 3px;
    font-size: 16px;
    cursor: pointer;
    margin-bottom: 15px;
}

/* Style du lien "S'inscrire" */
p a {
    color: #007BFF;
    text-decoration: none;
}

/* Style du lien "S'inscrire" au survol */
p a:hover {
    text-decoration: underline;
}

</style>
</html>

