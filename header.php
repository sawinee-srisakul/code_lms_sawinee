<?php

//Set session cookie to expire 
session_set_cookie_params(7200); // 2 hrs  =  7200
ini_set('session.gc_maxlifetime', 7200); // 2 hrs  = 7200

// Start the session if not started already
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the session has expired (no activity for 60 seconds)
if (isset($_SESSION['LAST_ACTIVITY']) && (time() - $_SESSION['LAST_ACTIVITY']) > 60) {
    // Session expired
    session_unset();     // Clear session variables
    session_destroy();   // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit;
}

// Update the last activity time to current time
$_SESSION['LAST_ACTIVITY'] = time();
?>
