<?php
session_start();

$serverName = "KYLELIX\SQLEXPRESS";
$connectionOptions = array("Database" => "db");
$conn = sqlsrv_connect($serverName, $connectionOptions);

$username = "";
$password = "";

if ($_POST["username"] != "") {
    $username = $_POST["username"];
}
if ($_POST["password"] != "") {
    $password = $_POST["password"];
}

$sql = "SELECT id, password FROM USERS WHERE username = '$username'";
$res = sqlsrv_query($conn, $sql);

$found = 0;
$dbPass = "";
$userID = 0;

?>
