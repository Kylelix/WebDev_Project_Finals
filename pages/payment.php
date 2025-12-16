<?php
session_start();
// db connection
require_once "../includes/db.php";

// get details
$bookingID = @$_GET["id"];
$amount = @$_GET["amount"];

// process payment
if (@$_POST["card_number"] != "") {
    $bid = $_POST["booking_id"];
    
    // update db
    $sql = "UPDATE BOOKINGS SET paymentStatus = 'Fully Paid' WHERE id = '$bid'";
    sqlsrv_query($conn, $sql);

    echo "<script>
        alert('Payment received, enjoy your trip!');
        window.location = 'profile.php';
    </script>";
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Secure Payment</title>
    <!-- viewport -->
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="stylesheet" href="../assets/css/homepage.css?v=<?php echo time(); ?>">
    <link rel="stylesheet" href="../assets/css/booking.css">
</head>
<body>

    <!-- navbar -->
    <?php require_once "../components/navbar.php"; ?>

    <div class="main-wrapper" style="max-width: 500px; width: 90%; display:block; padding: 40px; text-align:center; margin: 50px auto; background:#EEEEEE; border-radius:12px; border:1px solid #DCCFC0;">
        <h2 class="title" style="color: #A2AF9B;">Secure Payment</h2>
        <p>You are paying for Booking ID: #<?php echo $bookingID; ?></p>
        
        <h3 style="margin: 20px 0;">Total: P<?php echo $amount; ?></h3>

        <form method="POST">
            <input type="hidden" name="booking_id" value="<?php echo $bookingID; ?>">

            <div class="mb-3" style="text-align:left;">
                <label class="form-label">Card Number</label>
                <input type="text" name="card_number" class="form-control" placeholder="0000 0000 0000 0000" required>
            </div>

            <div class="row">
                <div class="col-6 mb-3" style="text-align:left;">
                    <label class="form-label">Expiry</label>
                    <input type="text" name="expiry" class="form-control" placeholder="MM/YY" required>
                </div>
                <div class="col-6 mb-3" style="text-align:left;">
                    <label class="form-label">CVV</label>
                    <input type="text" name="cvv" class="form-control" placeholder="123" required>
                </div>
            </div>

            <button class="btn w-100" style="background: #A2AF9B; color:white; font-weight:bold; margin-top: 20px;">Pay Now</button>
        </form>
        
        <br>
        <a href="profile.php" style="color: grey; text-decoration: none;">Cancel</a>
    </div>

    <!-- footer -->
    <?php require_once "../components/footer.php"; ?>

</body>
</html>
