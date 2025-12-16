console.log("JS Loaded!");
console.log("loadLocation() will run soon");

var locationCache = {};

function loadLocation(placeName, placeType, elementId) {

    if (placeName == "") {
        return;
    }

    // return from cache
    if (locationCache[placeName]) {
        document.getElementById(elementId).innerHTML = locationCache[placeName]["address"];
        return;
    }

    document.getElementById(elementId).innerHTML = "Loading...";

    var url = "ta_location.php?name=" + encodeURIComponent(placeName) + "&type=" + placeType;

    var request = new XMLHttpRequest();
    request.open("GET", url, true);

    request.onload = function () {

        if (request.status == 200) {

            var response = request.responseText;
            var data = null;

            // parse json
            try {
                data = JSON.parse(response);
            } catch (e) {
                document.getElementById(elementId).innerHTML = "Location unavailable";
                return;
            }

            if (data != null) {

                var address = data["address"];
                var lat = data["latitude"];
                var lon = data["longitude"];

                if (address != "") {

                    // save to cache
                    locationCache[placeName] = {
                        "address": address,
                        "lat": lat,
                        "lon": lon
                    };

                    document.getElementById(elementId).innerHTML = address;

                    // update item details
                    var i;

                    // hotels
                    for (i = 0; i < allHotels.length; i++) {
                        if (allHotels[i].name == placeName) {
                            allHotels[i]["address"] = address;
                            allHotels[i]["lat"] = lat;
                            allHotels[i]["lon"] = lon;
                        }
                    }

                    // restaurants
                    for (i = 0; i < allRestaurants.length; i++) {
                        if (allRestaurants[i].name == placeName) {
                            allRestaurants[i]["address"] = address;
                            allRestaurants[i]["lat"] = lat;
                            allRestaurants[i]["lon"] = lon;
                        }
                    }

                    // activities
                    for (i = 0; i < allActivities.length; i++) {
                        if (allActivities[i].name == placeName) {
                            allActivities[i]["address"] = address;
                            allActivities[i]["lat"] = lat;
                            allActivities[i]["lon"] = lon;
                        }
                    }

                } else {
                    document.getElementById(elementId).innerHTML = "Location unavailable";
                }
            }

        } else {
            document.getElementById(elementId).innerHTML = "Location unavailable";
        }
    };

    request.send();
}
