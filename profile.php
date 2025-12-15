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

<style>
body {
    background:#e7edf3;
    margin:0;
    font-family:Poppins, sans-serif;
}

/* USER CARD */
.info-card {
    width:420px;
    margin:30px auto;
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 4px 15px rgba(0,0,0,0.12);
    text-align:center;
}

/* BOOKING GRID */
.book-grid {
    width: 90%;
    margin: auto;
    margin-top: 30px;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 24px;
}

/* BOOKING CARD */
.booking-box {
    background: white;
    border-radius: 16px;
    box-shadow: 0 6px 18px rgba(0,0,0,0.12);
    overflow: hidden;
    text-align: center;
    padding-bottom: 18px;
}

/* TOP IMAGE */
.book-img {
    width: 100%;
    height: 180px;
    object-fit: cover;
}

/* TEXT SECTION */
.book-info {
    padding: 15px;
}

.book-info b {
    font-size: 18px;
    color: #1fa0ff;
    font-weight: 700;
    display: block;
    margin-bottom: 8px;
}

.book-info span {
    display: block;
    margin-top: 4px;
    font-size: 15px;
    color: #333;
    font-weight: 500;
}
</style>

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
