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


?>
