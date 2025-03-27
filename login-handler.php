<?php

include 'header.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    error_reporting(E_ALL);
    ini_set('display_errors', 1);

    include 'config.php';

    // Create connection
    $mysqli = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
    } else {
        $email = $password = "";
        $email = filter_var($_POST["email"], FILTER_VALIDATE_EMAIL);  
        $password = $_POST["password"];
    
        // Check if the user exists in the database
        $sql = "SELECT * FROM Users WHERE EmailAddress = ?";
        $stmt = $mysqli->prepare($sql);

        $stmt->bind_param("s", $email);
   
        if ($stmt === false) {
            die("Error preparing statement: " . $conn->error);
        }

        $stmt->bind_param("s", $email); // Bind the email to the query
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            // User found, now check the password
            $user = $result->fetch_assoc();

            if (password_verify($password, $user['PasswordHash'])) {
                // Password is correct, start the session and redirect to homepage
                $_SESSION['user_id'] = $user['MemberID'];
                $_SESSION['user_name'] = $user['FirstName'];

                if( $user['MemberType'] == "member") {
                    $_SESSION['success'] == "Loggin Success";
                    header("Location: member-dashboard.php"); // Redirect to homepage
                    exit;
                } else if ( $user['MemberType'] == "admin") {
                    $_SESSION['is_admin'] = true;
                    $_SESSION['success'] = "Loggin Success";
                    
                    header("Location: admin-dashboard.php"); // Redirect to homepage
                    exit;
                } else {
                    $_SESSION['error'] = "Invalid MemberType.";
                    header("Location: login.php"); // Redirect to homepage
                }

            } else {
                $_SESSION['error'] = "Invalid password.";
                header("Location: login.php"); // Redirect to homepage
            }
        } else {
            $_SESSION['error'] = "No user found with that email.";
            header("Location: login.php"); // Redirect to homepage
            
        }
        $stmt->close();
    }

        $mysqli->close();
        //echo "Connected successfully";

}


?>