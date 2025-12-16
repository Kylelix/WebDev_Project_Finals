<?php

$name = @$_GET["name"];
$type = @$_GET["type"];

if ($name == "") {
    echo json_encode(null);
    exit();
}

require_once "../includes/db.php";

// simulated search

$apiKey = "CF117863AC60432ABABC13AFD193329E";
$base = "https://api.content.tripadvisor.com/api/v1/location";

function trySearch($query, $apiKey, $base) {

    $searchUrl = $base . "/search?key=" . $apiKey . "&searchQuery=" . urlencode($query);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $searchUrl);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "Referer: https://abcdxd.com",
        "User-Agent: GalaExtremists/1.0"
    ));

    $result = curl_exec($ch);
    curl_close($ch);

    if ($result == "") {
        return "";
    }

    $data = json_decode($result, true);

    if ($data == null) {
        return "";
    }

    if (@$data["data"][0]["location_id"] != "") {
        return $data["data"][0]["location_id"];
    }

    return "";
}

// try full name
$locationId = trySearch($name, $apiKey, $base);

// remove last word
if ($locationId == "") {

    $parts = explode(" ", $name);
    $count = count($parts);

    if ($count > 1) {

        $newQuery = "";
        for ($i = 0; $i < $count - 1; $i++) {
            if ($i == 0) {
                $newQuery = $parts[$i];
            } else {
                $newQuery = $newQuery . " " . $parts[$i];
            }
        }

        $locationId = trySearch($newQuery, $apiKey, $base);
    }
}

// try first 3 words
if ($locationId == "") {

    $parts = explode(" ", $name);
    $count = count($parts);

    if ($count >= 3) {

        $newQuery = $parts[0] . " " . $parts[1] . " " . $parts[2];
        $locationId = trySearch($newQuery, $apiKey, $base);
    }
}

// try first 2 words
if ($locationId == "") {

    $parts = explode(" ", $name);
    $count = count($parts);

    if ($count >= 2) {

        $newQuery = $parts[0] . " " . $parts[1];
        $locationId = trySearch($newQuery, $apiKey, $base);
    }
}

$address = "";

// fetch details
if ($locationId != "") {

    $detailUrl = $base . "/" . $locationId . "/details?key=" . $apiKey;

    $ch2 = curl_init();
    curl_setopt($ch2, CURLOPT_URL, $detailUrl);
    curl_setopt($ch2, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch2, CURLOPT_HTTPHEADER, array(
        "Referer: https://abcdxd.com",
        "User-Agent: GalaExtremists/1.0"
    ));

    $result2 = curl_exec($ch2);
    curl_close($ch2);

    if ($result2 != "") {

        $info = json_decode($result2, true);

        if ($info != null) {

            if (@$info["address_obj"]["address_string"] != "") {
                $address = $info["address_obj"]["address_string"];
            } else {

                // city fallback
                if (@$info["address_obj"]["city"] != "") {
                    $address = $info["address_obj"]["city"];
                }
            }
        }
    }
}

// return json
echo json_encode(array("address" => $address));
?>
