<?php
session_start();

$token = @$_POST["token"];

if ($token == "") {
    echo "<script>alert('Missing Google token'); window.location='logreg.php';</script>";
    exit();
}

// decode token
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

if ($email == "") {
    echo "<script>alert('Unable to read email'); window.location='logreg.php';</script>";
    exit();
}

// db connection
require_once "../includes/db.php";

// check email
$sql = "SELECT id, username FROM USERS WHERE email = '$email'";
$res = sqlsrv_query($conn, $sql);

$found = 0;
$userid = 0;
$dbUsername = "";

if ($res) {
    while ($row = sqlsrv_fetch_array($res)) {
        $found = 1;
        $userid = $row["id"];
        $dbUsername = $row["username"];
    }
}

// login user
if ($found == 1) {
    $_SESSION["userID"] = $userid;
    $_SESSION["username"] = $dbUsername;

    header("Location: ../pages/home.php");
    exit();
}

// auto register
$username = $name;
if ($username == "") {
    $pos = strpos($email, "@");
    $username = substr($email, 0, $pos);
}

$password = "GoogleUser" . time();

$sql2 = "INSERT INTO USERS (email, username, password)
         VALUES ('$email', '$username', '$password')";
sqlsrv_query($conn, $sql2);

// get new id
$sql3 = "SELECT id FROM USERS WHERE email = '$email'";
$res3 = sqlsrv_query($conn, $sql3);

$newid = 0;

if ($res3) {
    while ($r = sqlsrv_fetch_array($res3)) {
        $newid = $r["id"];
    }
}

// set session
$_SESSION["userID"] = $newid;
$_SESSION["username"] = $username;

header("Location: ../pages/home.php");
exit();
?>
