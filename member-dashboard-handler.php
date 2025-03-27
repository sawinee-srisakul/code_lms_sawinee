<?php

include 'header.php';
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form values
    $bookId = $_POST['book_id'];
    $memberId = $_POST['member_id'];
    $borrowedDate = date('Y-m-d H:i:s');

    // Calculate the return due date (21 days later, for example)
    $dueDate = new DateTime($borrowedDate);
    $dueDate->modify('+21 days'); // Add 21 days to the borrowed date
    $returnDueDate = $dueDate->format('Y-m-d'); // Format it to 'Y-m-d'

    // Update the BookStatus table
    $query = "INSERT INTO BookStatus (BookID, MemberID, Status, BorrowedDate) 
              VALUES (?, ?, 'onloan', ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('iis', $bookId, $memberId, $borrowedDate);

    if ($stmt->execute()) {
        $_SESSION['borrow_success'] = "You have successfully borrowed the book! Your due date is " . $returnDueDate . " .";
 
    } else {
        $_SESSION['borrow_success'] = "An error occurred while borrowing the book.";
    }

    // Redirect back to the library page
    header('Location: member-dashboard.php');
    exit();
}

// Close the database connection
$conn->close();
?>
