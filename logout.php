<?php
session_start(); // Start the session
$_SESSION = array(); // Clear the session array
session_destroy(); // Destroy the session

header("Location: http://localhost/myFistWeb/main.php"); // Redirect to main.php
exit();
?>
