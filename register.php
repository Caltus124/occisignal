<!DOCTYPE html>
<html>
<head>
    <title>OcciSignal | Inscription</title>
    <link rel="stylesheet" type="text/css" href="styles.css">
</head>
<body>
    <form action="register_post.php" method="POST" class="two-columns-form">
        <div class="columns-container">
            <div class="column">
                <label for="nom">Nom :</label>
                <input type="text" id="nom" name="nom" required autocomplete="off">

                <label for="prenom">Prénom :</label>
                <input type="text" id="prenom" name="prenom" required autocomplete="off">

                <label for="nom_utilisateur">Nom d'utilisateur :</label>
                <input type="text" id="nom_utilisateur" name="nom_utilisateur" required autocomplete="off">
            </div>
            <div class="column">
                <label for="email">Adresse e-mail :</label>
                <input type="email" id="email" name="email" required autocomplete="off"> 

                <label for="mot_de_passe">Mot de passe :</label>
                <input type="password" id="mot_de_passe" name="mot_de_passe" required autocomplete="off">

                <label for="confirmer_mot_de_passe">Confirmer le mot de passe :</label>
                <input type="password" id="confirmer_mot_de_passe" name="confirmer_mot_de_passe" required autocomplete="off">
            </div>
        </div>
        <p>Vous avez un compte ? <a href="index.php">Se connecter</a></p>

        <input type="submit" value="S'inscrire">
        
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
    width: 600px;
    text-align: left;
    display: flex;
    flex-direction: column; /* Pour aligner le bouton en bas */
}

/* Style des colonnes */
.columns-container {
    display: flex;
    justify-content: space-between; /* Pour séparer les colonnes */
}

.column {
    flex: 1;
    padding: 10px;
}

.column label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
}

.column input {
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
    align-self: flex-end; /* Pour aligner le bouton en bas */
}

input{
    outline: none;
}


</style>
</html>


