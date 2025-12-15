<?php
header("Content-Type: application/javascript");

require_once "interests.php";

echo "var allHotels = " . json_encode($hotels) . ";";
echo "var allRestaurants = " . json_encode($restaurants) . ";";
echo "var allActivities = " . json_encode($activities) . ";";
?>