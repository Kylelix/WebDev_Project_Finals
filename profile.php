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

/* CHECK LOGIN */
if (@$_SESSION["userID"] == "") {
    echo "<script>alert('You must login first.');</script>";
    echo "<script>window.location='logreg.php';</script>";
    exit();
}

$userID = $_SESSION["userID"];
$username = $_SESSION["username"];

/* GET USER INFO FROM DATABASE */
$fullname = "";
$email = "";

$sqlUser = "SELECT email, username FROM USERS WHERE id='" . $userID . "'";
$resUser = sqlsrv_query($conn, $sqlUser);
$rowUser = sqlsrv_fetch_array($resUser);

if ($rowUser != null) {
    $email = $rowUser["email"];
}

/* BOOKINGS FROM DB */
$sql = "SELECT placeName, dateStart, dateEnd, pricePerDay, totalPrice 
        FROM BOOKINGS 
        WHERE userID='" . $userID . "' 
        ORDER BY id DESC";

$res = sqlsrv_query($conn, $sql);

$bookings = array();
$b = 0;

$row = sqlsrv_fetch_array($res);
while ($row != null) {
    $bookings[$b] = $row;
    $b = $b + 1;
    $row = sqlsrv_fetch_array($res);
}

/* LOAD INTEREST IMAGES */
require_once "interests.php";

/* LOOKUP IMAGE */
function getBookingImage($name, $hotels, $restaurants, $activities) {

    $count = count($hotels);
    for ($i = 0; $i < $count; $i = $i + 1) {
        if ($hotels[$i]["name"] == $name) {
            return $hotels[$i]["image"];
        }
    }

    $count = count($restaurants);
    for ($i = 0; $i < $count; $i = $i + 1) {
        if ($restaurants[$i]["name"] == $name) {
            return $restaurants[$i]["image"];
        }
    }

    $count = count($activities);
    for ($i = 0; $i < $count; $i = $i + 1) {
        if ($activities[$i]["name"] == $name) {
            return $activities[$i]["image"];
        }
    }

    return "placeholder.jpg";
}
?>

<!DOCTYPE html>
<html>
<head>
<title>My Profile</title>

<!-- Include homepage CSS to fix topbar layout -->
<link rel="stylesheet" href="css/homepage.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="css/profile.css">

</head>
<body>

<!-- TOP BAR SAME AS HOMEPAGE -->
<div class="topbar">
    <img src="images/logo.png" class="logo">

<div class="user-area">
    <a href="HomePage.php" class="menu-btn" style="background:#1fa0ff; margin-right:10px;">
        Return to Homepage
    </a>

    <a href="logout.php" class="menu-btn" style="background:#ff6b6b;">
        Logout
    </a>
</div>

</div>

<!-- USER INFO CARD -->
<div class="info-card">
    <h2>My Profile</h2>

    <p><b>Username:</b> <?php echo $username; ?></p>
    <p><b>Email:</b> <?php echo $email; ?></p>

</div>


<!-- BOOKINGS GRID -->
<div class="book-grid">
<?php
$total = count($bookings);

for ($i = 0; $i < $total; $i = $i + 1) {

    $name = $bookings[$i]["placeName"];
    $ppd = $bookings[$i]["pricePerDay"];
    $tp = $bookings[$i]["totalPrice"];

    /* DATE FIX */
    $ds = $bookings[$i]["dateStart"];
    if ($ds instanceof DateTime) {
        $ds = $ds->format("Y-m-d");
    }

    $de = $bookings[$i]["dateEnd"];
    if ($de instanceof DateTime) {
        $de = $de->format("Y-m-d");
    }

    /* IMAGE FETCH */
    $img = getBookingImage($name, $hotels, $restaurants, $activities);

    echo "<div class='booking-box'>";

    echo "<img src='images/" . $img . "' class='book-img' 
          onerror=\"this.src='images/placeholder.jpg'\">";

    echo "<div class='book-info'>";
    echo "<b>" . $name . "</b>";
    echo "<span>₱" . $ppd . " / day</span>";
    echo "<span>Total: ₱" . $tp . "</span>";
    echo "<span>" . $ds . " → " . $de . "</span>";
    echo "</div>";

    echo "</div>";
}
?>
</div>

</body>
</html>
