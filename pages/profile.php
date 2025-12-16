<?php
session_start();

// db connection
require_once "../includes/db.php";

// check login
if (@$_SESSION["userID"] == "") {
    echo "<script>alert('You must login first.');</script>";
    echo "<script>window.location='../auth/logreg.php';</script>";
    exit();
}

$userID = $_SESSION["userID"];
$username = $_SESSION["username"];

// get user info
$fullname = "";
$email = "";

$sqlUser = "SELECT email, username FROM USERS WHERE id='" . $userID . "'";
$resUser = sqlsrv_query($conn, $sqlUser);
$rowUser = sqlsrv_fetch_array($resUser);

if ($rowUser != null) {
    $email = $rowUser["email"];
}

// get bookings
$sql = "SELECT id, placeName, dateStart, dateEnd, pricePerDay, totalPrice, paymentStatus 
        FROM BOOKINGS 
        WHERE userID='" . $userID . "' 
        ORDER BY id DESC";

$res = sqlsrv_query($conn, $sql);

$bookings = array();
$b = 0;

if ($res) {
    while ($row = sqlsrv_fetch_array($res)) {
        $bookings[$b] = $row;
        $b = $b + 1;
    }
}

// load interests
require_once "../includes/interests.php";

// find image
function getBookingImage($name, $hotels, $restaurants, $activities) {

    $count = count($hotels);
    for ($i = 0; $i < $count; $i++) {
        if ($hotels[$i]["name"] == $name) {
            return $hotels[$i]["image"];
        }
    }

    $count = count($restaurants);
    for ($i = 0; $i < $count; $i++) {
        if ($restaurants[$i]["name"] == $name) {
            return $restaurants[$i]["image"];
        }
    }

    $count = count($activities);
    for ($i = 0; $i < $count; $i++) {
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

<!-- viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- css -->
<link rel="stylesheet" href="../assets/css/homepage.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../assets/css/profile.css?v=<?php echo time(); ?>">

</head>
<body>

<!-- navbar -->
<?php require_once "../components/navbar.php"; ?>

<!-- user info -->
<div class="info-card" style="margin-top: 50px;">
    <h2>My Profile</h2>

    <p><b>Username:</b> <?php echo $username; ?></p>
    <p><b>Email:</b> <?php echo $email; ?></p>

    <!-- buttons -->
    <div style="margin-top: 15px; display:flex; gap:10px; justify-content:center;">
        <button type="button" class="btn profile-action-btn" data-bs-toggle="modal" data-bs-target="#usernameModal">
          Change Username
        </button>
        <button type="button" class="btn profile-action-btn" data-bs-toggle="modal" data-bs-target="#passwordModal">
          Change Password
        </button>
    </div>
</div>


<!-- bookings -->
<div class="book-grid">
<?php
$total = count($bookings);

if ($total == 0) {
    echo "<p style='text-align:center;'>No bookings yet.</p>";
}

for ($i = 0; $i < $total; $i++) {

    $bid = $bookings[$i]["id"];
    $name = $bookings[$i]["placeName"];
    $ppd = $bookings[$i]["pricePerDay"];
    $tp = $bookings[$i]["totalPrice"];
    $status = $bookings[$i]["paymentStatus"]; 
    if ($status == "") { $status = "Not Paid"; }

    // fix date
    $ds = $bookings[$i]["dateStart"];
    if ($ds instanceof DateTime) {
        $ds = $ds->format("Y-m-d");
    }

    $de = $bookings[$i]["dateEnd"];
    if ($de instanceof DateTime) {
        $de = $de->format("Y-m-d");
    }

    // get image
    $img = getBookingImage($name, $hotels, $restaurants, $activities);

    echo "<div class='booking-box'>";
    
    echo "<img src='../assets/images/" . $img . "' class='book-img' 
          onerror=\"this.src='../assets/images/placeholder.jpg'\">";

    echo "<div class='book-info'>";
    echo "<b>" . $name . "</b>";
    echo "<span>P" . $ppd . " / day</span>";
    echo "<span>Total: P" . $tp . "</span>";
    echo "<span>" . $ds . " -> " . $de . "</span>";
    
    // payment logic
    if ($status == "Fully Paid") {
        echo "<div style='margin-top:15px; color:green; font-weight:bold;'>Status: Fully Paid</div>";
    } else {
        echo "<button class='pay-btn' onclick=\"window.location='payment.php?id=" . $bid . "&amount=" . $tp . "'\">Pay Now</button>";
    }

    echo "</div>";

    echo "</div>";
}
?>
</div>

<!-- bootstrap modals -->

<!-- username modal -->
<div class="modal fade" id="usernameModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Change Username</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../auth/change_credentials.php" method="POST">
          <div class="modal-body">
                <input type="hidden" name="action_type" value="username">
                
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" required class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">New Username</label>
                    <input type="text" name="new_value" required class="form-control">
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn profile-action-btn">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- password modal -->
<div class="modal fade" id="passwordModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5">Change Password</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form action="../auth/change_credentials.php" method="POST">
          <div class="modal-body">
                <input type="hidden" name="action_type" value="password">
                
                <div class="mb-3">
                    <label class="form-label">Current Password</label>
                    <input type="password" name="current_password" required class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" name="new_value" required class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Confirm New Password</label>
                    <input type="password" name="confirm_value" required class="form-control">
                </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            <button type="submit" class="btn profile-action-btn">Save changes</button>
          </div>
      </form>
    </div>
  </div>
</div>

</body>
</html>

</body>
</html>
