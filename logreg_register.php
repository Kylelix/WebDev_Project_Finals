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

?>
