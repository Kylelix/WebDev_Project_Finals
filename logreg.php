<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Login / Register</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

</head>

<body>

<div class="outer">

    <div class="logreg-card">

        <!-- LOGIN -->
        <div class="left-panel">
            <h2>Login</h2>

            <form method="POST" action="logreg_login.php">
                <label>Username</label>
                <input type="text" name="username" class="logreg-box">

                <label>Password</label>
                <input type="password" name="password" class="logreg-box">

                <button class="logreg-btn">Login</button>
            </form>
        </div>

        <div class="divider"></div>

        <!-- REGISTER -->
        <div class="right-panel">
            <h2>Register</h2>

            <form method="POST" action="logreg_register.php">
                <label>Email</label>
                <input type="text" name="email" class="logreg-box">

                <label>Username</label>
                <input type="text" name="username" class="logreg-box">

                <label>Password</label>
                <input type="password" name="password" class="logreg-box">

                <button class="logreg-btn">Register</button>
            </form>
        </div>

    </div>

</body>
</html>
