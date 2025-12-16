<?php
session_start();

require_once "../includes/db.php";

$email = @$_POST["email"];
$username = @$_POST["username"];
$password = @$_POST["password"];

// check email
$sql = "SELECT id FROM USERS WHERE email = '$email'";
$res = sqlsrv_query($conn, $sql);

$exists = 0;

if ($res) {
    while ($row = sqlsrv_fetch_array($res)) {
        $exists = 1;
    }
}

if ($exists == 1) {
    echo "
    <script>
    alert('Email already taken.');
    window.location='logreg.php';
    </script>
    ";
    exit();
}

// insert user
$sql2 = "INSERT INTO USERS (email, username, password)
         VALUES ('$email', '$username', '$password')";

sqlsrv_query($conn, $sql2);

echo "
<script>
alert('Registration complete! Please login.');
window.location='logreg.php';
</script>
";
?>
