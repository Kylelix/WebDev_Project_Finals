<?php
session_start();
require_once "interests.php";

/* SUPPRESS WARNINGS WHILE CREATING SESSION KEYS */
$_SESSION["username"] = @$_SESSION["username"];
$_SESSION["userID"] = @$_SESSION["userID"];

/* CHECK IF USER IS NOT LOGGED IN - REDIRECT TO index.html */
if ($_SESSION["username"] == "" || $_SESSION["userID"] == "") {
    header("Location: index.html");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Homepage</title>

<!-- MAPBOX -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

<link rel="stylesheet" href="css/homepage.css?v=<?php echo time(); ?>">
<script src="js/location_fetch.js?v=<?php echo time(); ?>"></script>
<script src="js/homepage.js?v=<?php echo time(); ?>"></script>

</head>
<body>

<!-- TOP BAR -->
<div class="topbar">
    <img src="images/hotel_logo.png" class="logo">

        <!-- ADDED: Navigation menu -->
    <div class="nav-menu">
        <a href="#welcome" class="nav-link">Home</a>
        <a href="#destinations" class="nav-link">Destinations</a>
        <a href="about.php" class="nav-link">About</a>
        <a href="contact.php" class="nav-link">Contact</a>
    </div>

    <div class='user-area'>
        <span class='welcome-text'>Welcome, <?php echo $_SESSION["username"]; ?></span>
        <a href='profile.php' class='menu-btn'>Profile</a>
        <a href='logout.php' class='menu-btn' style='background:#ff6b6b;'>Logout</a>
    </div>
</div>

<!-- ADDED: Welcome screen with scrolling images -->
<div id="welcome" class="welcome-section">
    <div class="welcome-overlay">
        <h1 class="welcome-title">Discover Your Next Adventure</h1>
        <p class="welcome-subtitle">Explore the best hotels, restaurants, and activities</p>
    </div>
    <div class="welcome-slider">
        <div class="slider-track">
            <img src="images/img1.jpg" class="slider-img">
            <img src="images/img2.jpg" class="slider-img">
            <img src="images/img3.jpg" class="slider-img">
            <img src="images/img4.jpg" class="slider-img">
            <img src="images/img5.jpg" class="slider-img">
            <img src="images/img6.jpg" class="slider-img">
            <img src="images/img7.jpg" class="slider-img">
            <img src="images/img8.jpg" class="slider-img">
            <img src="images/img9.jpg" class="slider-img">
            <img src="images/img10.jpg" class="slider-img">
            <!-- Duplicate for loop -->
            <img src="images/img1.jpg" class="slider-img">
            <img src="images/img2.jpg" class="slider-img">
            <img src="images/img3.jpg" class="slider-img">
            <img src="images/img4.jpg" class="slider-img">
            <img src="images/img5.jpg" class="slider-img">
            <img src="images/img6.jpg" class="slider-img">
            <img src="images/img7.jpg" class="slider-img">
            <img src="images/img8.jpg" class="slider-img">
            <img src="images/img9.jpg" class="slider-img">
            <img src="images/img10.jpg" class="slider-img">
        </div>
    </div>
</div>

<!-- MAPBOX DISPLAY -->
<div id="mapbox-container">
    <div id="map"></div>
</div>


<!-- SEARCH BAR -->
<div id="destinations" class="search-bar">
    <input type="text" id="searchBox" class="search-input" placeholder="Search a place...">
    <button class="search-btn" onclick="applySearch()">Search</button>
</div>


<!-- PASS PHP ARRAYS TO JS -->
<script>
var allHotels = <?php echo json_encode($hotels); ?>;
var allRestaurants = <?php echo json_encode($restaurants); ?>;
var allActivities = <?php echo json_encode($activities); ?>;
</script>


<!-- 3 COLUMNS -->
<div class="columns">

    <!-- HOTELS -->
    <div class="col">
        <div class="col-head">
            <div class="col-title">HOTELS</div>
            <div class="col-arrows">
                <button class="arrow" onclick="prevHotel()">&lt;</button>
                <button class="arrow" onclick="nextHotel()">&gt;</button>
            </div>
        </div>

        <div class="col-body">
            <img id="hotel-img" class="item-img">
            <div id="hotel-name" class="item-name"></div>
            <div id="hotel-loc" class="location-text"></div>

            <div class="card-actions">
                <a id="hotel-book" class="book-btn-small" href="#">Book</a>
                <button id="hotel-map-btn" class="map-btn-small" onclick="loadSelectedMap('hotel')">Load Map</button>
            </div>
        </div>
    </div>

    <!-- RESTAURANTS -->
    <div class="col">
        <div class="col-head">
            <div class="col-title">RESTAURANTS</div>
            <div class="col-arrows">
                <button class="arrow" onclick="prevRestaurant()">&lt;</button>
                <button class="arrow" onclick="nextRestaurant()">&gt;</button>
            </div>
        </div>

        <div class="col-body">
            <img id="restaurant-img" class="item-img">
            <div id="restaurant-name" class="item-name"></div>
            <div id="restaurant-loc" class="location-text"></div>

            <div class="card-actions">
                <a id="restaurant-book" class="book-btn-small" href="#">Book</a>
                <button id="restaurant-map-btn" class="map-btn-small" onclick="loadSelectedMap('restaurant')">Load Map</button>
            </div>
        </div>
    </div>

    <!-- ACTIVITIES -->
    <div class="col">
        <div class="col-head">
            <div class="col-title">ACTIVITIES</div>
            <div class="col-arrows">
                <button class="arrow" onclick="prevActivity()">&lt;</button>
                <button class="arrow" onclick="nextActivity()">&gt;</button>
            </div>
        </div>

        <div class="col-body">
            <img id="activity-img" class="item-img">
            <div id="activity-name" class="item-name"></div>
            <div id="activity-loc" class="location-text"></div>

            <div class="card-actions">
                <a id="activity-book" class="book-btn-small" href="#">Book</a>
                <button id="activity-map-btn" class="map-btn-small" onclick="loadSelectedMap('activity')">Load Map</button>
            </div>
        </div>
    </div>

</div>


<!-- MAPBOX JS LOGIC -->
<script>
mapboxgl.accessToken = "pk.eyJ1Ijoia3lsZWxpeCIsImEiOiJjbWl3ejMzdmIwMWU5M2VxczJyOHBxbXZ2In0.2GzAyBPJlO_X24QBTy-MYQ";

var map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/streets-v11",
    center: [120.9842, 14.5995],
    zoom: 10
});

var activeMarker = null;

function loadSelectedMap(type) {

    var list = null;
    var index = 0;

    if (type == "hotel") {
        list = hotels;
        index = hIndex;
    }

    if (type == "restaurant") {
        list = restaurants;
        index = rIndex;
    }

    if (type == "activity") {
        list = activities;
        index = aIndex;
    }

    var place = list[index];

    if (place == null) {
        alert("No place loaded.");
        return;
    }

    var address = place["address"];

    if (address == null || address == "") {
        alert("No address available.");
        return;
    }

    /* GEOCODE USING ADDRESS ONLY */
    var url = "https://api.mapbox.com/geocoding/v5/mapbox.places/" +
    encodeURIComponent(address) +
    ".json?access_token=" + mapboxgl.accessToken;

    fetch(url)
    .then(function(r){ return r.json(); })
    .then(function(data){

        if (data.features.length > 0) {

            var lon = data.features[0].center[0];
            var lat = data.features[0].center[1];

            moveMap(lon, lat);

        } else {
            alert("MapBox could not find this address.");
        }
    });
}

function moveMap(lon, lat) {

    if (activeMarker != null) {
        activeMarker.remove();
    }

    activeMarker = new mapboxgl.Marker().setLngLat([lon, lat]).addTo(map);

    map.flyTo({
        center: [lon, lat],
        zoom: 14
    });
}

window.onload = function() {
    initializeHome();
};
</script>

</body>
</html>