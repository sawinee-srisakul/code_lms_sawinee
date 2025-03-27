<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

$servername = "localhost";
$username = "admin_lms_sawinee_srisakul";
$password = "BDTE2r3nZ4Bd7ENk";
$dbname = "lms_sawinee_srisakul";

// Create connection
//$conn = new mysqli($servername, $username, $password);
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}
echo "Connected successfully";
?>