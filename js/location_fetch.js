console.log("JS Loaded!");
console.log("loadLocation() will run soon");

var locationCache = {};

function loadLocation(placeName, placeType, elementId) {

    if (placeName == "") {
        return;
    }

    /* If cached, return the cached address */
    if (locationCache[placeName]) {
        document.getElementById(elementId).innerHTML = locationCache[placeName]["address"];
        return;
    }

    document.getElementById(elementId).innerHTML = "Loading...";

    var url = "ta_location.php?name=" + encodeURIComponent(placeName) + "&type=" + placeType;

    var request = new XMLHttpRequest();
    request.open("GET", url, true);

    request.onload = function() {

        if (request.status == 200) {

            var response = request.responseText;
            var data = null;

            /* BASIC PARSE ONLY (no try/catch allowed except this one which was already present) */
            try {
                data = JSON.parse(response);
            } catch(e) {
                document.getElementById(elementId).innerHTML = "Location unavailable";
                return;
            }

            if (data != null) {

                var address = data["address"];
                var lat = data["latitude"];
                var lon = data["longitude"];

                if (address != "") {

                    /* SAVE INTO CACHE */
                    locationCache[placeName] = {
                        "address": address,
                        "lat": lat,
                        "lon": lon
                    };

                    document.getElementById(elementId).innerHTML = address;

                    /* SAVE INTO THE ITEM OBJECT ITSELF */

                    var i;

                    /* HOTELS */
                    for (i = 0; i < allHotels.length; i++) {
                        if (allHotels[i].name == placeName) {
                            allHotels[i]["address"] = address;
                            allHotels[i]["lat"] = lat;
                            allHotels[i]["lon"] = lon;
                        }
                    }

                    /* RESTAURANTS */
                    for (i = 0; i < allRestaurants.length; i++) {
                        if (allRestaurants[i].name == placeName) {
                            allRestaurants[i]["address"] = address;
                            allRestaurants[i]["lat"] = lat;
                            allRestaurants[i]["lon"] = lon;
                        }
                    }

                    /* ACTIVITIES */
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
