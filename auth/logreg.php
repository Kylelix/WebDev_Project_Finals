<?php
session_start();
?>

<!DOCTYPE html>
<html>
<head>
<title>Login / Register</title>

<!-- viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<!-- bootstrap -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

<!-- css -->
<link rel="stylesheet" href="../assets/css/homepage.css?v=<?php echo time(); ?>">
<link rel="stylesheet" href="../assets/css/logreg.css?v=<?php echo time(); ?>">

<script src="https://accounts.google.com/gsi/client" async defer></script>

</head>

<body>

<?php require_once "../components/navbar.php"; ?>

<div class="outer">

    <div class="logreg-card">

        <!-- login -->
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

        <!-- register -->
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

    <!-- google code -->
    <div class="google-wrapper">
        <div id="g_id_onload"
             data-client_id="397070442652-a36b7hmfeah7sag869fsrgqdcpkvcrs6.apps.googleusercontent.com"
             data-context="signin"
             data-ux_mode="popup"
             data-callback="handleGoogle"
             data-auto_prompt="false">
        </div>

        <div class="g_id_signin"
             data-type="standard"
             data-size="medium"
             data-theme="outline"
             data-text="signin_with"
             data-shape="rectangular">
        </div>
    </div>

</div>


<script>
function handleGoogle(response) {
    let f = document.createElement("form");
    f.method = "POST";
    f.action = "google_unified.php";

    let i = document.createElement("input");
    i.type = "hidden";
    i.name = "token";
    i.value = response.credential;

    f.appendChild(i);
    document.body.appendChild(f);
    f.submit();
}
</script>

</body>
</html>
