var hIndex = 0;
var rIndex = 0;
var aIndex = 0;

var hotels = [];
var restaurants = [];
var activities = [];

window.currentHotelIndex = 0;
window.currentRestaurantIndex = 0;
window.currentActivityIndex = 0;

/* start up */
function initializeHome() {
    hotels = allHotels;
    restaurants = allRestaurants;
    activities = allActivities;

    updateHotel();
    updateRestaurant();
    updateActivity();
}

/* filter basic search */
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

/* login check */
function checkLoginAndBook(name, type) {
    if (isLoggedIn == "") {
        alert("Please login to book.");
        window.location = "../auth/logreg.php";
    } else {
        window.location = "booking.php?name=" + encodeURIComponent(name) + "&type=" + type;
    }
}

/* hotel carousel functions */
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
    if (hotels.length == 0) {
        document.getElementById("hotel-name").innerHTML = "No hotels found";
        document.getElementById("hotel-loc").innerHTML = "Location unavailable";
        document.getElementById("hotel-img").src = "../assets/images/placeholder.jpg";
        return;
    }

    var item = hotels[hIndex];

    document.getElementById("hotel-img").src = "../assets/images/" + item.image;
    document.getElementById("hotel-name").innerHTML = item.name;

    // book button
    var btn = document.getElementById("hotel-book");
    btn.href = "javascript:void(0)";
    btn.onclick = function () { checkLoginAndBook(item.name, "hotels"); };

    loadLocation(item.name, "hotels", "hotel-loc");
}

/* restaurant carousel functions */
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
    if (restaurants.length == 0) {
        document.getElementById("restaurant-name").innerHTML = "No restaurants found";
        document.getElementById("restaurant-loc").innerHTML = "Location unavailable";
        document.getElementById("restaurant-img").src = "../assets/images/placeholder.jpg";
        return;
    }

    var item = restaurants[rIndex];

    document.getElementById("restaurant-img").src = "../assets/images/" + item.image;
    document.getElementById("restaurant-name").innerHTML = item.name;

    var btn = document.getElementById("restaurant-book");
    btn.href = "javascript:void(0)";
    btn.onclick = function () { checkLoginAndBook(item.name, "restaurants"); };

    loadLocation(item.name, "restaurants", "restaurant-loc");
}

/* activity carousel functions */
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
    if (activities.length == 0) {
        document.getElementById("activity-name").innerHTML = "No activities found";
        document.getElementById("activity-loc").innerHTML = "Location unavailable";
        document.getElementById("activity-img").src = "../assets/images/placeholder.jpg";
        return;
    }

    var item = activities[aIndex];

    document.getElementById("activity-img").src = "../assets/images/" + item.image;
    document.getElementById("activity-name").innerHTML = item.name;

    var btn = document.getElementById("activity-book");
    btn.href = "javascript:void(0)";
    btn.onclick = function () { checkLoginAndBook(item.name, "activities"); };

    loadLocation(item.name, "activities", "activity-loc");
}

/* advanced search filter logic */
function runAdvancedSearch() {
    var keyword = document.getElementById("advKeyword").value.toLowerCase();
    var type = document.getElementById("advType").value;
    var price = document.getElementById("advPrice").value;

    var results = [];

    /* combine all */
    var pool = [];

    // Tag items with types
    var i;
    for (i = 0; i < allHotels.length; i++) {
        var item = allHotels[i];
        item.type = "hotel";
        pool.push(item);
    }
    for (i = 0; i < allRestaurants.length; i++) {
        var item = allRestaurants[i];
        item.type = "restaurant";
        pool.push(item);
    }
    for (i = 0; i < allActivities.length; i++) {
        var item = allActivities[i];
        item.type = "activity";
        pool.push(item);
    }

    // loop and check filters
    for (i = 0; i < pool.length; i++) {
        var p = pool[i];
        var match = true;

        /* keyword match */
        if (keyword != "") {
            if (p.name.toLowerCase().indexOf(keyword) == -1) {
                match = false;
            }
        }

        /* type match */
        if (type != "all") {
            if (p.type != type) {
                match = false;
            }
        }

        /* price range check */
        // Prices are now integers in the array
        if (price != "any") {
            if (price == "budget" && p.price > 2000) match = false;
            if (price == "standard" && (p.price <= 2000 || p.price > 5000)) match = false;
            if (price == "luxury" && p.price <= 5000) match = false;
        }

        if (match) {
            results.push(p);
        }
    }

    displayAdvancedResults(results);
}

function displayAdvancedResults(list) {
    var container = document.getElementById("adv-results");
    container.innerHTML = "";

    document.getElementById("result-count").innerHTML = list.length + " result(s) found";

    if (list.length == 0) {
        container.innerHTML = "<p style='width:100%; text-align:center;'>No matches found.</p>";
        return;
    }

    for (var i = 0; i < list.length; i++) {
        var item = list[i];
        var typeLabel = item.type.charAt(0).toUpperCase() + item.type.slice(1);

        // Link to booking
        // using correct plural types for consistency with carousel logic: 'hotels', 'restaurants', 'activities'
        // item.type is singular 'hotel' etc from the pool tagging
        var typePlural = item.type + "s";

        // no long string concats inside onclick if possible, but simplest way for beginner code:
        var clickFunc = "checkLoginAndBook('" + item.name.replace(/'/g, "\\'") + "', '" + typePlural + "')";

        var card = "";
        card += "<div class='adv-card'>";
        card += "  <img src='../assets/images/" + item.image + "' class='adv-img' onerror=\"this.src='../assets/images/placeholder.jpg'\">";
        card += "  <div class='adv-info'>";
        card += "    <div class='adv-meta'>" + typeLabel + "</div>";
        card += "    <div class='adv-name'>" + item.name + "</div>";
        card += "    <div class='adv-price'>Start from ₱" + item.price + "</div>";
        card += "    <a href='javascript:void(0)' onclick=\"" + clickFunc + "\" class='adv-book-btn'>Book</a>";
        card += "  </div>";
        card += "</div>";

        container.innerHTML += card;
    }
}

// map loading handled in home.php
