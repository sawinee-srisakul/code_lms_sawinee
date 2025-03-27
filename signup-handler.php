<?php
include 'header.php'; 
include 'config.php';

    // Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {


error_reporting(E_ALL);
ini_set('display_errors', 1);


$mysqli = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($mysqli->connect_error) {
  die("Connection failed: " . $mysqli->connect_error);
} else {

    $fname = $lname = $email = $password = $confirm_password = "";
    $role = 'member'; // Default role
    $fname = $_POST['fname'];
    $lname = $_POST["lname"];
    $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);  
    $password = $_POST["password"];
    $confirm_password = $_POST["confirm_password"];     
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);
    $role = $_POST["role"];
               
    // Insert user details into the database
    $sql = "INSERT INTO Users (FirstName, LastName, EmailAddress, PasswordHash, MemberType) VALUES (?, ?, ?, ?, ?)";
    $stmt = $mysqli->prepare( $sql );
    $stmt->bind_param("sssss", $fname, $lname, $email, $hashed_password, $role);
    if ($stmt->execute()) {
        echo "insert successfully";
        $_SESSION['success'] = "Registration successful. You can now login.";
        header("Location: login.php");
        exit();
    } else {
        echo "insert failed";
        $errors[] = "Error: Could not register user.";
    }
        $stmt->close();
}

        $mysqli->close();
echo "Connected successfully";

}



?>