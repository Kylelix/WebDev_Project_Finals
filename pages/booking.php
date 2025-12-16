<?php
session_start();
require_once "../includes/interests.php";

$name = @$_GET["name"];
$type = @$_GET["type"];

$place = null;

$tcount = count($hotels);
for ($i = 0; $i < $tcount; $i++) {
    if ($hotels[$i]["name"] == $name) {
        $place = $hotels[$i];
        break;
    }
}

if ($place == null) {
    $tcount = count($restaurants);
    for ($i = 0; $i < $tcount; $i++) {
        if ($restaurants[$i]["name"] == $name) {
            $place = $restaurants[$i];
            break;
        }
    }
}

if ($place == null) {
    $tcount = count($activities);
    for ($i = 0; $i < $tcount; $i++) {
        if ($activities[$i]["name"] == $name) {
            $place = $activities[$i];
            break;
        }
    }
}

if ($place == null) {
    echo "<p>Place not found.</p>";
    exit();
}

$displayName = $place["name"];
$displayImg = $place["image"];

// fallback price logic
$displayPrice = 2000;
if (@$place["price"] != "") {
    $displayPrice = $place["price"];
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking - <?php echo htmlspecialchars($displayName); ?></title>

<!-- viewport for mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- bootstrap -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="../assets/css/homepage.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../assets/css/booking.css?v=<?php echo time(); ?>">

</head>
<body>

<!-- navbar -->
<?php require_once "../components/navbar.php"; ?>

<div class="booking-container">

    <div class="booking-grid">
        <!-- left side image and details -->
        <div class="booking-details">
            <img src="../assets/images/<?php echo $displayImg; ?>" 
                 class="booking-img"
                 onerror="this.onerror=null; this.src='../assets/images/placeholder.jpg';">
            
            <div class="details-content">
                <h2><?php echo htmlspecialchars($displayName); ?></h2>
                <span class="price-tag">₱<?php echo number_format($displayPrice); ?> / night</span>
                <p class="desc-text">
                    Experience the best services and amenities. Located in a prime spot, 
                    this destination offers an unforgettable experience for you and your family.
                </p>
                <div class="rules">
                    <ul>
                        <li>Free Wi-Fi</li>
                        <li>Breakfast Included</li>
                        <li>24/7 Support</li>
                    </ul>
                </div>
            </div>
        </div>

        <!-- right side form -->
        <div class="booking-form-wrapper">
            <div class="form-header">
                <h3>Book Your Stay</h3>
                <p>Fill in your details below</p>
            </div>

            <form method="POST" action="bookingcheck.php">

                <input type="hidden" name="placeName" value="<?php echo htmlspecialchars($displayName); ?>">
                <input type="hidden" name="placeType" value="<?php echo htmlspecialchars($type); ?>">

                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="fullName" required>
                </div>

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" required>
                </div>

                <div class="row-group">
                    <div class="form-group half">
                        <label>Arrival</label>
                        <input type="date" name="arrivalDate" required>
                    </div>
                    <div class="form-group half">
                        <label>Departure</label>
                        <input type="date" name="departureDate" required>
                    </div>
                </div>

                <div class="row-group">
                    <div class="form-group half">
                        <label>Adults</label>
                        <select name="numAdults">
                            <?php for($i=1;$i<=10;$i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </div>
                    <div class="form-group half">
                        <label>Children</label>
                        <select name="numChildren">
                            <?php for($i=0;$i<=10;$i++) echo "<option value='$i'>$i</option>"; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label>Special Requests</label>
                    <textarea name="message" rows="3"></textarea>
                </div>

                <button class="book-now-btn">Confirm Booking</button>
            </form>
        </div>
    </div>

</div>

<!-- footer -->
<?php require_once "../components/footer.php"; ?>

</body>
</html>
