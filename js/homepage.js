var hIndex = 0;
var rIndex = 0;
var aIndex = 0;

var hotels = [];
var restaurants = [];
var activities = [];

window.currentHotelIndex = 0;
window.currentRestaurantIndex = 0;
window.currentActivityIndex = 0;

/* INITIALIZE HOME */
function initializeHome() {
    hotels = allHotels;
    restaurants = allRestaurants;
    activities = allActivities;

    updateHotel();
    updateRestaurant();
    updateActivity();
}

/* SEARCH FILTER */
function applySearch() {
    var q = document.getElementById("searchBox").value.toLowerCase();

    hotels = [];
    restaurants = [];
    activities = [];

    var i;

    for (i = 0; i < allHotels.length; i = i + 1) {
        if (allHotels[i].name.toLowerCase().indexOf(q) != -1) {
            hotels.push(allHotels[i]);
        }
    }

    for (i = 0; i < allRestaurants.length; i = i + 1) {
        if (allRestaurants[i].name.toLowerCase().indexOf(q) != -1) {
            restaurants.push(allRestaurants[i]);
        }
    }

    for (i = 0; i < allActivities.length; i = i + 1) {
        if (allActivities[i].name.toLowerCase().indexOf(q) != -1) {
            activities.push(allActivities[i]);
        }
    }

    hIndex = 0;
    rIndex = 0;
    aIndex = 0;

    updateHotel();
    updateRestaurant();
    updateActivity();
}

/* ===========================
      HOTEL CONTROLS
=========================== */
function nextHotel() {
    if (hotels.length > 0) {
        hIndex = (hIndex + 1) % hotels.length;
        updateHotel();
    }
}

function prevHotel() {
    if (hotels.length > 0) {
        hIndex = (hIndex - 1 + hotels.length) % hotels.length;
        updateHotel();
    }
}

function updateHotel() {
    if (hotels.length === 0) {
        document.getElementById("hotel-name").innerHTML = "No hotels found";
        document.getElementById("hotel-loc").innerHTML = "Location unavailable";
        document.getElementById("hotel-img").src = "images/placeholder.jpg";
        return;
    }

    var item = hotels[hIndex];

    document.getElementById("hotel-img").src = "images/" + item.image;
    document.getElementById("hotel-name").innerHTML = item.name;
    document.getElementById("hotel-book").href =
        "booking.php?name=" + encodeURIComponent(item.name) + "&type=hotels";

    loadLocation(item.name, "hotels", "hotel-loc");
}

/* ===========================
    RESTAURANT CONTROLS
=========================== */
function nextRestaurant() {
    if (restaurants.length > 0) {
        rIndex = (rIndex + 1) % restaurants.length;
        updateRestaurant();
    }
}

function prevRestaurant() {
    if (restaurants.length > 0) {
        rIndex = (rIndex - 1 + restaurants.length) % restaurants.length;
        updateRestaurant();
    }
}

function updateRestaurant() {
    if (restaurants.length === 0) {
        document.getElementById("restaurant-name").innerHTML = "No restaurants found";
        document.getElementById("restaurant-loc").innerHTML = "Location unavailable";
        document.getElementById("restaurant-img").src = "images/placeholder.jpg";
        return;
    }

    var item = restaurants[rIndex];

    document.getElementById("restaurant-img").src = "images/" + item.image;
    document.getElementById("restaurant-name").innerHTML = item.name;
    document.getElementById("restaurant-book").href =
        "booking.php?name=" + encodeURIComponent(item.name) + "&type=restaurants";

    loadLocation(item.name, "restaurants", "restaurant-loc");
}

/* ===========================
      ACTIVITY CONTROLS
=========================== */
function nextActivity() {
    if (activities.length > 0) {
        aIndex = (aIndex + 1) % activities.length;
        updateActivity();
    }
}

function prevActivity() {
    if (activities.length > 0) {
        aIndex = (aIndex - 1 + activities.length) % activities.length;
        updateActivity();
    }
}

function updateActivity() {
    if (activities.length === 0) {
        document.getElementById("activity-name").innerHTML = "No activities found";
        document.getElementById("activity-loc").innerHTML = "Location unavailable";
        document.getElementById("activity-img").src = "images/placeholder.jpg";
        return;
    }

    var item = activities[aIndex];

    document.getElementById("activity-img").src = "images/" + item.image;
    document.getElementById("activity-name").innerHTML = item.name;
    document.getElementById("activity-book").href =
        "booking.php?name=" + encodeURIComponent(item.name) + "&type=activities";

    loadLocation(item.name, "activities", "activity-loc");
}

/* ===========================
     MAPBOX MOVE FUNCTION
=========================== */
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

var activeMarker = null;

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