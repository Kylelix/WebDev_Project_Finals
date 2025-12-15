<?php
session_start();

/* DB CONNECT */
$serverName = "KYLELIX\SQLEXPRESS";
$connectionOptions = array(
    "Database" => "db",
    "Uid" => "",
    "PWD" => ""
);

$conn = sqlsrv_connect($serverName, $connectionOptions);

/* READ INPUTS */
$place = "";
$full = "";
$email = "";
$phone = "";
$arrive = "";
$depart = "";
$adults = "";
$kids = "";
$msg = "";

if ($_POST["placeName"] != "") { $place = $_POST["placeName"]; }
if ($_POST["fullName"] != "") { $full = $_POST["fullName"]; }
if ($_POST["email"] != "") { $email = $_POST["email"]; }
if ($_POST["phone"] != "") { $phone = $_POST["phone"]; }
if ($_POST["arrivalDate"] != "") { $arrive = $_POST["arrivalDate"]; }
if ($_POST["departureDate"] != "") { $depart = $_POST["departureDate"]; }
if ($_POST["numAdults"] != "") { $adults = $_POST["numAdults"]; }
if ($_POST["numChildren"] != "") { $kids = $_POST["numChildren"]; }
if ($_POST["message"] != "") { $msg = $_POST["message"]; }

/* USER ID */
$userid = 0;

if (@$_SESSION["userID"] != "") {
    $userid = $_SESSION["userID"];
}

/* PRICE LOGIC */
$pricePerDay = 1000;  
$startTime = strtotime($arrive);
$endTime = strtotime($depart);

$days = 1;

if ($endTime > $startTime) {
    $diff = $endTime - $startTime;
    $days = $diff / 86400;
}

$total = $days * $pricePerDay;

/* INSERT INTO DB */
$sql = "
INSERT INTO BOOKINGS (userID, placeName, dateStart, dateEnd, message, pricePerDay, totalPrice)
VALUES ('$userid', '$place', '$arrive', '$depart', '$msg', '$pricePerDay', '$total')
";

sqlsrv_query($conn, $sql);

/* REDIRECT */
header("Location: profile.php");
exit();
?>
