<?php
session_start();

// public page

$sent = 0;
if (@$_POST["contact_msg"] != "") {
    $sent = 1;
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Contact Us</title>
<!-- viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- font -->
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="../assets/css/homepage.css?v=<?php echo time(); ?>">
</head>

<body>

<!-- navbar -->
<?php require_once "../components/navbar.php"; ?>

<div class="contact-container" style="margin-top: 50px;">
    
    <?php if ($sent == 0) { ?>

    <h2 class="contact-title">Contact Us</h2>

    <form method="POST" action="contactus.php">
        
        <div class="form-group">
            <label class="form-label">Full Name</label>
            <input type="text" name="full_name" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Email Address</label>
            <input type="email" name="email" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Phone Number (Optional)</label>
            <input type="text" name="phone" class="form-input">
        </div>

        <div class="form-group">
            <label class="form-label">Subject</label>
            <input type="text" name="subject" class="form-input" required>
        </div>

        <div class="form-group">
            <label class="form-label">Message</label>
            <textarea name="contact_msg" class="form-textarea" rows="5" required></textarea>
        </div>

        <button class="submit-contact">Send Message</button>

    </form>
    
    <!-- about us section -->
    <div style="margin-top: 60px; margin-bottom: 40px; border-top: 1px solid #ddd; padding-top: 30px;">
        <h2 class="contact-title" style="font-size: 24px;">Who Are We?</h2>
        <div style="background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 5px rgba(0,0,0,0.05);">
            <p style="color: #555; line-height: 1.8; font-size: 15px;">
                Honestly, we're just a small team of friends who really love travelling around the Philippines. 
                We started this project because we felt that finding good spots shouldn't be so hard or confusing. 
                We aren't some big faceless corporation; we are just regular people who want to share the best local gems with you.
                <br><br>
                Whether you are looking for a fancy hotel, a quick bite to eat, or a fun weekend activity, we hope our little collection helps you find exactly what you need. 
                Thanks for stopping by!
            </p>
        </div>
    </div>

    <?php } else { ?>

        <h2 class="contact-title">Message Sent!</h2>
        <p style="text-align:center;">Thank you for contacting us. We will get back to you shortly.</p>
        <div style="text-align:center; margin-top:20px;">
            <a href="home.php" class="menu-btn">Return Home</a>
        </div>

    <?php } ?>

</div>

<!-- footer -->
<?php require_once "../components/footer.php"; ?>

</body>
</html>
