<?php
// Database configuration
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "admin_lms_sawinee_srisakul";
$password = "BDTE2r3nZ4Bd7ENk";
$dbname = "lms_sawinee_srisakul";

try {
    $conn = new mysqli($servername, $username, $password, $dbname);

    if($conn->connect_error) {
        throw new Exception("Connection failed: " . $conn->connect_error);
    }
} catch (Exception $e) {
    // Store error message in the session and redirect to an error page
    $_SESSION['errors'][] = $e->getMessage();
    header("Location: error.php"); // Redirect to an error page
    exit();
}

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}


?>