<?php
session_start();

// load data
require_once "../includes/interests.php";

// suppress warnings for session vars
$_SESSION["username"] = @$_SESSION["username"];
$_SESSION["userID"] = @$_SESSION["userID"];

// page is public so no redirect
?>

<!DOCTYPE html>
<html>
<head>
<title>Homepage</title>

<!-- viewport for mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- mapbox api -->
<link href="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.css" rel="stylesheet" />
<script src="https://api.mapbox.com/mapbox-gl-js/v2.15.0/mapbox-gl.js"></script>

<!-- font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- stylesheet and js -->
<link rel="stylesheet" href="../assets/css/homepage.css?v=<?php echo time(); ?>">
<script src="../assets/js/location_fetch.js?v=<?php echo time(); ?>"></script>
<script src="../assets/js/homepage.js?v=<?php echo time(); ?>"></script>

</head>
<body>

<!-- navbar -->
<?php require_once "../components/navbar.php"; ?>

<!-- welcome section -->
<div id="welcome" class="welcome-section">
    <div class="welcome-overlay">
        <h1 class="welcome-title">Discover Your Next Adventure</h1>
        <p class="welcome-subtitle">Explore the best hotels, restaurants, and activities</p>
    </div>
    <div class="welcome-slider">
        <div class="slider-track">
            <!-- manual images -->
            <img src="../assets/images/img1.jpg" class="slider-img">
            <img src="../assets/images/img2.jpg" class="slider-img">
            <img src="../assets/images/img3.jpg" class="slider-img">
            <img src="../assets/images/img4.jpg" class="slider-img">
            <img src="../assets/images/img5.jpg" class="slider-img">
            <img src="../assets/images/img1.jpg" class="slider-img">
            <img src="../assets/images/img2.jpg" class="slider-img">
            <img src="../assets/images/img3.jpg" class="slider-img">
            <img src="../assets/images/img4.jpg" class="slider-img">
            <img src="../assets/images/img5.jpg" class="slider-img">
        </div>
    </div>
</div>

<!-- search -->
<div id="destinations" class="search-bar">
    <input type="text" id="searchBox" class="search-input" placeholder="Search a place...">
    <button class="search-btn" onclick="applySearch()">Search</button>
</div>

<!-- pass php arrays to js -->
<script>
var allHotels = <?php echo json_encode($hotels); ?>;
var allRestaurants = <?php echo json_encode($restaurants); ?>;
var allActivities = <?php echo json_encode($activities); ?>;
var isLoggedIn = "<?php echo $_SESSION["userID"]; ?>";
</script>

<!-- columns -->
<div class="columns">

    <!-- hotels column -->
    <div class="col">
        <div class="col-head">
            <div class="col-title">HOTELS</div>
            <div class="col-arrows">
                <button class="arrow" onclick="prevHotel()">&lt;</button>
                <button class="arrow" onclick="nextHotel()">&gt;</button>
            </div>
        </div>

        <div class="col-body">
            <img id="hotel-img" class="item-img" src="../assets/images/placeholder.jpg">
            <div id="hotel-name" class="item-name"></div>
            <div id="hotel-loc" class="location-text"></div>

            <div class="card-actions">
                <a id="hotel-book" class="book-btn-small" href="#">Book</a>
                <button id="hotel-map-btn" class="map-btn-small" onclick="loadSelectedMap('hotel')">Load Map</button>
            </div>
        </div>
    </div>

    <!-- restaurants column -->
    <div class="col">
        <div class="col-head">
            <div class="col-title">RESTAURANTS</div>
            <div class="col-arrows">
                <button class="arrow" onclick="prevRestaurant()">&lt;</button>
                <button class="arrow" onclick="nextRestaurant()">&gt;</button>
            </div>
        </div>

        <div class="col-body">
            <img id="restaurant-img" class="item-img" src="../assets/images/placeholder.jpg">
            <div id="restaurant-name" class="item-name"></div>
            <div id="restaurant-loc" class="location-text"></div>

            <div class="card-actions">
                <a id="restaurant-book" class="book-btn-small" href="#">Book</a>
                <button id="restaurant-map-btn" class="map-btn-small" onclick="loadSelectedMap('restaurant')">Load Map</button>
            </div>
        </div>
    </div>

    <!-- activities column -->
    <div class="col">
        <div class="col-head">
            <div class="col-title">ACTIVITIES</div>
            <div class="col-arrows">
                <button class="arrow" onclick="prevActivity()">&lt;</button>
                <button class="arrow" onclick="nextActivity()">&gt;</button>
            </div>
        </div>

        <div class="col-body">
            <img id="activity-img" class="item-img" src="../assets/images/placeholder.jpg">
            <div id="activity-name" class="item-name"></div>
            <div id="activity-loc" class="location-text"></div>

            <div class="card-actions">
                <a id="activity-book" class="book-btn-small" href="#">Book</a>
                <button id="activity-map-btn" class="map-btn-small" onclick="loadSelectedMap('activity')">Load Map</button>
            </div>
        </div>
    </div>

</div>

<!-- map -->
<div id="mapbox-container">
    <div id="map"></div>
</div>

<!-- advanced search -->
<div id="advanced-search" class="adv-search-section">
    <div class="adv-header">
        <h2>Find Your Perfect Experience</h2>
        <p>Filter by type, price, or keyword.</p>
    </div>

    <div class="filter-bar">
        <!-- keyword input -->
        <input type="text" id="advKeyword" placeholder="e.g. Resort, Café..." class="adv-input">

        <!-- type dropdown -->
        <select id="advType" class="adv-select">
            <option value="all">All Types</option>
            <option value="hotel">Hotels</option>
            <option value="restaurant">Restaurants</option>
            <option value="activity">Activities</option>
        </select>

        <!-- price dropdown -->
        <select id="advPrice" class="adv-select">
            <option value="any">Any Price</option>
            <option value="budget">Budget (Under ₱2000)</option>
            <option value="standard">Standard (₱2000 - ₱5000)</option>
            <option value="luxury">Luxury (Above ₱5000)</option>
        </select>

        <button onclick="runAdvancedSearch()" class="adv-btn">Filter</button>
    </div>

    <!-- results will go here -->
    <div id="adv-results" class="adv-grid">
        <!-- js will fill this -->
    </div>
    
    <div style="text-align:center; margin-top:30px;">
        <p id="result-count" style="color:#777;"></p>
    </div>
</div>

<!-- mapbox setup script -->
<script>
mapboxgl.accessToken = "pk.eyJ1Ijoia3lsZWxpeCIsImEiOiJjbWl3ejMzdmIwMWU5M2VxczJyOHBxbXZ2In0.2GzAyBPJlO_X24QBTy-MYQ";

// setup map
var map = new mapboxgl.Map({
    container: "map",
    style: "mapbox://styles/mapbox/streets-v11",
    center: [120.9842, 14.5995],
    zoom: 10
});

var activeMarker = null;

// load map for a specific item
function loadSelectedMap(type) {

    // scroll down to map
    document.getElementById("mapbox-container").scrollIntoView({behavior: "smooth"});

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

    if (list.length == 0) {
        alert("No items to load.");
        return;
    }

    var place = list[index];
    var address = place["address"];

    if (address == "") {
        alert("No address available.");
        return;
    }

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
            alert("Location not found on map.");
        }
    });
}

// move the pin
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
    runAdvancedSearch(); // show defaults
};
</script>

<!-- FOOTER -->
<?php require_once "../components/footer.php"; ?>

</body>
</html>