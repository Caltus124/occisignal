<?php
session_start();
if (!isset($_SESSION['type_utilisateur'])) {
    header('Location: login.php'); 
}
?>
<div id="map" style="width: 100%; height: 100vh;"></div>

<script>
    // Fonction d'initialisation de la carte
    function initMap() {
        // Coordonnées du centre de la carte
        var myLatLng = {lat: 43.600000, lng:  1.433333};

        // Créer une nouvelle carte Google Maps
        var map = new google.maps.Map(document.getElementById('map'), {
            center: myLatLng,
            zoom: 12.8 // Niveau de zoom initial
        });

        // Récupérer les coordonnées des marqueurs à partir de la base de données (PHP)
        <?php
        require_once('bdd.php'); // Assurez-vous d'inclure votre fichier de connexion à la base de données

        $sql = "SELECT latitude, longitude FROM tickets WHERE latitude IS NOT NULL AND longitude IS NOT NULL";
        $result = $conn->query($sql);

        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $lat = $row["latitude"];
                $lng = $row["longitude"];
        ?>
        // Créer un marqueur pour chaque coordonnée
        var marker = new google.maps.Marker({
            position: { lat: <?php echo $lat; ?>, lng: <?php echo $lng; ?> },
            map: map,
            title: 'Marqueur'
        });
        <?php
            }
        }
        $conn->close();
        ?>

    }

    // Appeler la fonction d'initialisation une fois que la page est chargée
    google.maps.event.addDomListener(window, 'load', initMap);
</script>
