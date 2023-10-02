<?php
    session_start();
    session_destroy();
    header('Location: login.php'); // Rediriger vers la page de connexion après la déconnexion
?>
