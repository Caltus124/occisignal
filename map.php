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

        // Marqueur sur la carte
        var coordinates = [
            { lat: 43.6047, lng: 1.47 },
            { lat: 43.605, lng: 1.445 },
            { lat: 43.606, lng: 1.460 },
            { lat: 43.607, lng: 1.490 },
            { lat: 43.610, lng: 1.415 },
            { lat: 43.600, lng: 1.400 },
            { lat: 43.610, lng: 1.410 },
            { lat: 43.611, lng: 1.451 },
            { lat: 43.612, lng: 1.452 },
            { lat: 43.650, lng: 1.460 }
        ];

        // Créer 10 marqueurs aléatoires
        for (var i = 0; i < 10; i++) {
            var randomIndex = Math.floor(Math.random() * coordinates.length);
            var randomCoordinate = coordinates.splice(randomIndex, 1)[0];

            var marker = new google.maps.Marker({
                position: randomCoordinate,
                map: map,
                title: 'Marqueur ' + (i + 1)
            });
        }

    }

    // Appeler la fonction d'initialisation une fois que la page est chargée
    google.maps.event.addDomListener(window, 'load', initMap);
</script>