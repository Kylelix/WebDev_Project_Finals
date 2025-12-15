<?php
session_start();

/* GET GOOGLE TOKEN */
$token = @$_POST["token"];
if ($token == "") {
    echo "<script>alert('Missing Google token'); window.location='logreg.php';</script>";
    exit();
}

/* DECODE JWT */
$parts = explode(".", $token);
$payload = $parts[1];

$remain = strlen($payload) % 4;
if ($remain > 0) {
    $pad = 4 - $remain;
    $payload .= str_repeat("=", $pad);
}

$payload = str_replace("-", "+", $payload);
$payload = str_replace("_", "/", $payload);

$data = json_decode(base64_decode($payload), true);

$email = $data["email"];
$name  = $data["name"];

/* VALIDATE EMAIL */
if ($email == "") {
    echo "<script>alert('Unable to read email'); window.location='logreg.php';</script>";
    exit();
}

/* SQLSRV CONNECT */
$serverName = "KYLELIX\SQLEXPRESS";
$connectionOptions = array("Database" => "db");
$conn = sqlsrv_connect($serverName, $connectionOptions);

/* CHECK IF USER EXISTS */
$sql = "SELECT id, username FROM USERS WHERE email = '$email'";
$res = sqlsrv_query($conn, $sql);

$found = 0;
$userid = 0;
$dbUsername = "";

while ($row = sqlsrv_fetch_array($res)) {
    $found = 1;
    $userid = $row["id"];
    $dbUsername = $row["username"];
}

/* LOGIN IF FOUND */
if ($found == 1) {

    $_SESSION["userID"] = $userid;
    $_SESSION["username"] = $dbUsername;

    header("Location: homepage.php");
    exit();
}

/* AUTO REGISTER NEW GOOGLE USER */
$username = $name;
if ($username == "") {
    $pos = strpos($email, "@");
    $username = substr($email, 0, $pos);
}

$password = "GoogleUser" . time();

$sql2 = "INSERT INTO USERS (email, username, password)
         VALUES ('$email', '$username', '$password')";
sqlsrv_query($conn, $sql2);

/* GET NEW ID */
$sql3 = "SELECT id FROM USERS WHERE email = '$email'";
$res3 = sqlsrv_query($conn, $sql3);

$newid = 0;

while ($r = sqlsrv_fetch_array($res3)) {
    $newid = $r["id"];
}

/* SET SESSION */
$_SESSION["userID"] = $newid;
$_SESSION["username"] = $username;

header("Location: homepage.php");
exit();
?>
