<div class="topbar">
    <img src="../assets/images/hotel_logo.png" class="logo">
    <h1 class="logo-text">Destinatours</h1>

    <div class="nav-menu">
        <a href="../pages/home.php" class="nav-link">Home</a>
        <a href="../pages/home.php#advanced-search" class="nav-link">Search</a>
        <a href="../pages/home.php#destinations" class="nav-link">Destinations</a>
        <a href="../pages/contactus.php" class="nav-link">Contact Us</a>
    </div>

    <div class="user-area">
        <?php if (@$_SESSION["username"] == "") { ?>
            <!-- guest view -->
            <a href="../auth/logreg.php" class="menu-btn">Login / Register</a>
        <?php } else { ?>
            <!-- user view -->
            <span class="welcome-text">Welcome, <span class="user-name-highlight"><?php echo $_SESSION["username"]; ?></span></span>
            <a href="../pages/profile.php" class="menu-btn">Profile</a>
            <a href="../auth/logout.php" class="menu-btn logout-btn">Logout</a>
        <?php } ?>
    </div>
</div>
