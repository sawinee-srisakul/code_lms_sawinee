<?php
session_start(); // Start the session

// Destroy all session data
session_unset(); // Unset all session variables
session_destroy(); // Destroy the session

// Redirect to login page or homepage
header("Location: login.php"); // Or you can use index.php if you want to redirect to homepage
exit();
?>