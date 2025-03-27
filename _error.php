<?php

session_start();

// Check if there are any error messages
if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])) {
    // Display the error messages
    echo "<div class='error-messages'>";
    foreach ($_SESSION['errors'] as $error) {
        echo "<p style='color: red;'>$error</p>"; // Display each error in red
    }
    echo "</div>";

    // Clear the error messages after displaying them
    unset($_SESSION['errors']);
}

?>