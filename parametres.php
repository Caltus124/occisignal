<?php
// Assurez-vous que vous avez une connexion à la base de données ici


$conn = new mysqli("localhost", "caltus", "root", "signalement");

// Vérification de la connexion
if ($conn->connect_error) {
    die("La connexion à la base de données a échoué : " . $conn->connect_error);
}


$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez l'ancien mot de passe, le nouveau mot de passe et sa confirmation depuis le formulaire
    $ancienMotDePasse = hash('sha256', $_POST["ancien_mot_de_passe"]); // Hachage SHA-256 de l'ancien mot de passe
    $nouveauMotDePasse = $_POST["nouveau_mot_de_passe"];
    $confirmerMotDePasse = $_POST["confirmer_mot_de_passe"];
    $idUtilisateur = $_POST["id_utilisateur"]; // L'identifiant de l'utilisateur fourni

    // Assurez-vous de valider et de sécuriser les données entrées ici

    // Vérifiez si l'ancien mot de passe correspond au mot de passe stocké en base de données
    // Vous devez d'abord récupérer le mot de passe actuel de l'utilisateur depuis la base de données
    $sql = "SELECT mot_de_passe FROM user WHERE id = ?";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $idUtilisateur);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows == 1) {
            // L'utilisateur a été trouvé, vérifiez le mot de passe
            $stmt->bind_result($motDePasseStocke);
            $stmt->fetch();

            if ($ancienMotDePasse === $motDePasseStocke) {
                // Le mot de passe correspond, vérifiez la confirmation du nouveau mot de passe
                if ($nouveauMotDePasse === $confirmerMotDePasse) {
                    // Le nouveau mot de passe et sa confirmation correspondent, mettez à jour le mot de passe
                    $nouveauMotDePasseHache = hash('sha256', $nouveauMotDePasse); // Hachage SHA-256 du nouveau mot de passe
                    $sql = "UPDATE user SET mot_de_passe = ? WHERE id = ?";

                    if ($stmt = $conn->prepare($sql)) {
                        $stmt->bind_param("si", $nouveauMotDePasseHache, $idUtilisateur);

                        if ($stmt->execute()) {
                            $message = "Mot de passe mis à jour avec succès.";
                            session_start();
                            session_destroy();
                            header('Location: login.php');
                        } else {
                            $message = "Une erreur s'est produite lors de la modification du mot de passe.";
                        }

                        $stmt->close();
                    }
                } else {
                    $message = "La confirmation du nouveau mot de passe ne correspond pas.";
                }
            } else {
                $message = "L'ancien mot de passe est incorrect.";
            }
        } else {
            $message = "Utilisateur non trouvé.";
        }

        $stmt->close();
    }
}
// Fermez la connexion à la base de données
$conn->close();
?>

    <style>
        /* Style général pour le formulaire */
        form {
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            margin-top: 50px;
        }

        label {
            display: block;
            margin-bottom: 10px;
            font-weight: bold;
            color: #333;
        }

        input[type="password"] {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        input[type="text"] {
            display: none;
        }

        input[type="submit"] {
            display: block;
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: #fff;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .result-message {
            text-align: center;
            margin-top: 20px;
            padding: 10px;
            font-weight: bold;
        }

        .success-message {
            color: green;
        }

        .error-message {
            color: red;
        }


    </style>


    <div class="form-container">
    <h1>Modifier le Mot de Passe</h1>

    <form action="main.php?page=parametres" method="post">
        <input type="text" name="id_utilisateur" value="1"> <!-- Remplacez par l'ID de l'utilisateur que vous avez déjà -->
        <label for="ancien_mot_de_passe">Ancien mot de passe :</label>
        <input type="password" name="ancien_mot_de_passe" id="ancien_mot_de_passe" required>
        <br>
        <label for="nouveau_mot_de_passe">Nouveau mot de passe :</label>
        <input type="password" name="nouveau_mot_de_passe" id="nouveau_mot_de_passe" required>
        <br>
        <label for="confirmer_mot_de_passe">Confirmer le nouveau mot de passe :</label>
        <input type="password" name="confirmer_mot_de_passe" id="confirmer_mot_de_passe" required>
        <br>
        <input type="submit" value="Modifier le mot de passe">
    </form>
    <div class="result-message <?php echo strpos($message, 'succès') !== false ? 'success-message' : 'error-message'; ?>">
        <?php echo $message; // Afficher le message de résultat ?>
    </div>
</div>


