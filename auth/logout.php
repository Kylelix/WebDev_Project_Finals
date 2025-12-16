<?php
session_start();

$_SESSION["userID"] = "";
$_SESSION["username"] = "";

session_destroy();

echo "
<script>
alert('You have been logged out.');
window.location = '../pages/index.html';
</script>
";
?>
