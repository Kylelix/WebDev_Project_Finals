<?php
session_start();

require_once "../includes/db.php";

$username = @$_POST["username"];
$password = @$_POST["password"];

// simple query
$sql = "SELECT id, password FROM USERS WHERE username = '$username'";
$res = sqlsrv_query($conn, $sql);

$found = 0;
$dbPass = "";
$userID = 0;

if ($res) {
    while ($row = sqlsrv_fetch_array($res)) {
        $found = 1;
        $dbPass = $row["password"];
        $userID = $row["id"];
    }
}

if ($found == 1) {
    if ($password == $dbPass) {
        $_SESSION["userID"] = $userID;
        $_SESSION["username"] = $username;

        // go to homepage
        header("Location: ../pages/home.php");
        exit();
    }
}

echo "
<script>
alert('Invalid login.');
window.location='logreg.php';
</script>
";
?>
