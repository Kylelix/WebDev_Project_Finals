<?php
require_once "interests.php";

$name = "";
if (@$_GET["name"] != "") {
    $name = $_GET["name"];
}

$type = "";
if (@$_GET["type"] != "") {
    $type = $_GET["type"];
}

$place = null;

$tcount = count($hotels);
for ($i = 0; $i < $tcount; $i = $i + 1) {
    if ($hotels[$i]["name"] == $name) {
        $place = $hotels[$i];
        break;
    }
}

if ($place == null) {
    $tcount = count($restaurants);
    for ($i = 0; $i < $tcount; $i = $i + 1) {
        if ($restaurants[$i]["name"] == $name) {
            $place = $restaurants[$i];
            break;
        }
    }
}

if ($place == null) {
    $tcount = count($activities);
    for ($i = 0; $i < $tcount; $i = $i + 1) {
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
?>

<!DOCTYPE html>
<html>
<head>
<title>Booking - <?php echo htmlspecialchars($displayName); ?></title>

<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<link rel="stylesheet" href="css/booking.css">

</head>
<body>

<div class="main-wrapper">

    <!-- FULL LEFT IMAGE -->
    <div class="left-section">
        <img src="images/<?php echo $displayImg; ?>" 
             class="place-image"
             onerror="this.onerror=null; this.src='images/placeholder.jpg';">
    </div>

    <!-- RIGHT FORM -->
    <div class="right-section">

        <div class="title">
            Inquiry / Booking for "<?php echo htmlspecialchars($displayName); ?>"
        </div>

        <form method="POST" action="bookingcheck.php">

            <input type="hidden" name="placeName" value="<?php echo htmlspecialchars($displayName); ?>">
            <input type="hidden" name="placeType" value="<?php echo htmlspecialchars($type); ?>">

            <div class="form-group">
                <label>Your Full Name *</label>
                <input type="text" name="fullName" class="full-input">
            </div>

            <div class="form-group">
                <label>Email Address *</label>
                <input type="email" name="email" class="full-input">
            </div>

            <div class="form-group">
                <label>Phone Number *</label>
                <input type="text" name="phone" class="small-input">
            </div>

            <div class="half-row">
                <div class="form-group" style="width:50%;">
                    <label>Arrival Date *</label>
                    <input type="date" name="arrivalDate" class="half-input">
                </div>
                <div class="form-group" style="width:50%;">
                    <label>Departure Date *</label>
                    <input type="date" name="departureDate" class="half-input">
                </div>
            </div>

            <div class="half-row">
                <div class="form-group">
                    <label>Adults *</label>
                    <select name="numAdults" class="small-input">
                        <?php
                        for ($i = 1; $i <= 10; $i = $i + 1) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Children</label>
                    <select name="numChildren" class="small-input">
                        <?php
                        for ($i = 0; $i <= 10; $i = $i + 1) {
                            echo "<option value='$i'>$i</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>

            <div class="form-group">
                <label>Additional Requests</label>
                <textarea name="message" rows="4"></textarea>
            </div>

            <button class="submit-btn">Submit Inquiry</button>
        </form>

    </div>

</div>

</body>
</html>
