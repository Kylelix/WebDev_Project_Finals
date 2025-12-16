<?php
// server name
$serverName = "KYLELIX\SQLEXPRESS";

// settings
$connectionOptions = array(
    "Database" => "db",
    "Uid" => "",
    "PWD" => ""
);

// connect
$conn = sqlsrv_connect($serverName, $connectionOptions);
?>
