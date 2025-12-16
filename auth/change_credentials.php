<?php
session_start();
require_once "../includes/db.php";

$userID = $_SESSION["userID"];
if ($userID == "") {
    echo "<script>alert('Please login.'); window.location='logreg.php';</script>";
    exit();
}

$actionType = @$_POST["action_type"];
$currentPass = @$_POST["current_password"];
$newValue = @$_POST["new_value"];
$confirmValue = @$_POST["confirm_value"];

// check current password
$sqlCheck = "SELECT password FROM USERS WHERE id='$userID'";
$resCheck = sqlsrv_query($conn, $sqlCheck);
$rowCheck = sqlsrv_fetch_array($resCheck);

$dbPass = "";
if ($rowCheck) {
    $dbPass = $rowCheck["password"];
}

if ($currentPass != $dbPass) {
    echo "<script>alert('Incorrect current password.'); window.location='../pages/profile.php';</script>";
    exit();
}

// update username
if ($actionType == "username") {
    if ($newValue == "") {
        echo "<script>alert('Username cannot be empty.'); window.location='../pages/profile.php';</script>";
        exit();
    }

    $sqlUp = "UPDATE USERS SET username='$newValue' WHERE id='$userID'";
    sqlsrv_query($conn, $sqlUp);
    
    $_SESSION["username"] = $newValue;
    echo "<script>alert('Username updated!'); window.location='../pages/profile.php';</script>";
}

// update password
else if ($actionType == "password") {
    if ($newValue == "") {
        echo "<script>alert('Password cannot be empty.'); window.location='../pages/profile.php';</script>";
        exit();
    }

    if ($newValue != $confirmValue) {
        echo "<script>alert('New passwords do not match.'); window.location='../pages/profile.php';</script>";
        exit();
    }

    $sqlUp = "UPDATE USERS SET password='$newValue' WHERE id='$userID'";
    sqlsrv_query($conn, $sqlUp);

    echo "<script>alert('Password updated!'); window.location='../pages/profile.php';</script>";
}
?>
