<?php
session_start();

// db connection
require_once "../includes/db.php";
require_once "../includes/interests.php";

// inputs
$place = @$_POST["placeName"];
$full = @$_POST["fullName"];
$email = @$_POST["email"];
$phone = @$_POST["phone"];
$arrive = @$_POST["arrivalDate"];
$depart = @$_POST["departureDate"];
$adults = @$_POST["numAdults"];
$kids = @$_POST["numChildren"];
$msg = @$_POST["message"];

// check login
$userid = 0;

if (@$_SESSION["userID"] != "") {
    $userid = $_SESSION["userID"];
}

// find the price logic
$pricePerDay = 1500; // default

// check hotels
for ($i = 0; $i < count($hotels); $i++) {
    $h = $hotels[$i];
    if ($h["name"] == $place) {
        if (@$h["price"] != "") {
            $pricePerDay = $h["price"];
        } else {
            $pricePerDay = 2000;
        }
        break;
    }
}

// check restaurants
for ($i = 0; $i < count($restaurants); $i++) {
    $r = $restaurants[$i];
    if ($r["name"] == $place) {
        if (@$r["price"] != "") {
            $pricePerDay = $r["price"];
        } else {
            $pricePerDay = 1000;
        }
        break;
    }
}

// check activities
for ($i = 0; $i < count($activities); $i++) {
    $a = $activities[$i];
    if ($a["name"] == $place) {
        if (@$a["price"] != "") {
            $pricePerDay = $a["price"];
        } else {
            $pricePerDay = 1500;
        }
        break;
    }
}

$startTime = strtotime($arrive);
$endTime = strtotime($depart);

// validating dates
if ($startTime >= $endTime) {
    echo "<script>
        alert('Invalid dates');
        window.location='booking.php?name=' + '" . urlencode($place) . "';
    </script>";
    exit();
}

$days = 1;

if ($endTime > $startTime) {
    $diff = $endTime - $startTime;
    $days = $diff / 86400;
}

$total = $days * $pricePerDay;

// sanitize string inputs
$place = str_replace("'", "''", $place);
$full = str_replace("'", "''", $full);
$msg = str_replace("'", "''", $msg);

// save to database
$sql = "
INSERT INTO BOOKINGS (userID, placeName, dateStart, dateEnd, message, pricePerDay, totalPrice, paymentStatus)
VALUES ('$userid', '$place', '$arrive', '$depart', '$msg', '$pricePerDay', '$total', 'Not Paid')
";

sqlsrv_query($conn, $sql);

// easy redirect
header("Location: profile.php");
exit();
?>
