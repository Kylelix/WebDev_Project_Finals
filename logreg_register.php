<?php
session_start();

$serverName = "KYLELIX\SQLEXPRESS";
$connectionOptions = array("Database" => "db");
$conn = sqlsrv_connect($serverName, $connectionOptions);

$email = "";
$username = "";
$password = "";

if ($_POST["email"] != "") { $email = $_POST["email"]; }
if ($_POST["username"] != "") { $username = $_POST["username"]; }
if ($_POST["password"] != "") { $password = $_POST["password"]; }

/* CHECK DUPLICATE EMAIL */
$sql = "SELECT id FROM USERS WHERE email = '$email'";
$res = sqlsrv_query($conn, $sql);

$exists = 0;

while ($row = sqlsrv_fetch_array($res)) {
    $exists = 1;
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
