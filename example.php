<?php
// No PHP logic needed, but file must be .php so you can run it directly.
?>

<!DOCTYPE html>
<html>
<head>
<title>MapBox Example</title>

<!-- MapBox GL CSS -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.css" rel="stylesheet" />

<style>
body {
    margin:0;
    font-family: Arial, sans-serif;
    background:#e7edf3;
}

#map {
    width: 100%;
    height: 400px;
    border-bottom: 4px solid #1fa0ff;
}

.button-box {
    padding:20px;
    text-align:center;
}

.map-btn {
    padding:10px 18px;
    background:#1fa0ff;
    color:white;
    border-radius:8px;
    text-decoration:none;
    border:none;
    font-weight:600;
    font-size:16px;
    cursor:pointer;
}

</style>
</head>
<body>

<!-- MAP CONTAINER -->
<div id="map"></div>

<!-- BUTTON TO RUN A BASIC API CALL -->
<div class="button-box">
    <button class="map-btn" onclick="searchPlace()">Search for Manila</button>
</div>

<!-- MapBox JS -->
<script src="https://api.mapbox.com/mapbox-gl-js/v2.11.0/mapbox-gl.js"></script>

<script>
// YOUR MAPBOX TOKEN HERE
var token = "pk.eyJ1Ijoia3lsZWxpeCIsImEiOiJjbWl3ejMzdmIwMWU5M2VxczJyOHBxbXZ2In0.2GzAyBPJlO_X24QBTy-MYQ";


// INITIALIZE MAP
mapboxgl.accessToken = token;

var map = new mapboxgl.Map({
    container: 'map',
    style: 'mapbox://styles/mapbox/streets-v11',
    center: [120.9842, 14.5995],  // Manila
    zoom: 10
});

// BASIC GEOCODING EXAMPLE
function searchPlace() {
    var place = "Manila";
    var url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" + place + ".json?access_token=" + token;

    fetch(url)
    .then(function(response) {
        return response.json();
    })
    .then(function(data) {
        var lon = data.features[0].center[0];
        var lat = data.features[0].center[1];

        alert("Manila Coordinates:\nLatitude: " + lat + "\nLongitude: " + lon);

        // Optionally move the map to the result:
        map.flyTo({ center: [lon, lat], zoom: 12 });
    });
}
</script>

</body>
</html>
