<?php
include 'header.php';
include 'config.php';

// Handle Add Book
if (isset($_POST['action']) && $_POST['action'] == 'add') {

    // Get form data
    $bookTitle = $_POST['bookTitle'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $language = $_POST['language'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $memberId = $_SESSION['user_id'];

    // Debug: print form data
    error_log("Adding book: $bookTitle, $author, $publisher, $language, $category");

    $coverImageFileName = "images-cover/placeholder_book.jpg"; // Default placeholder image path

    // Check if a cover image is uploaded
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {


        $coverImage = $_FILES['cover_image']['name'];

        // required to run chmod -R 755 images-cover in terminal!!!!!!!!!
        // Specify the target directory for the images
        $targetDir = "images-cover/";  // Images will be saved in the 'images' folder
        $targetFile = $targetDir . basename($coverImage);

        // Move the uploaded image to the 'images' directory
        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $targetFile)) {
            // Debug: Image uploaded successfully
            error_log("Cover image uploaded successfully: $targetFile");

            // Use the uploaded image as the cover image
            $coverImageFileName = $targetFile;
        } else {
            // Log the error for debugging
            error_log("Error uploading cover image: " . $_FILES["cover_image"]["error"]);
        }
    } else {
        // Log that no valid image was uploaded
        error_log("No valid cover image uploaded. Using placeholder.");
    }

    // Insert book data into Books table
    $query = "INSERT INTO Books (BookTitle, Author, Publisher, Language, Category, CoverImagePath) 
              VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssssss', $bookTitle, $author, $publisher, $language, $category, $coverImageFileName);

    if ($stmt->execute()) {
        // Get the inserted BookID
        $bookId = $conn->insert_id;

        // Insert default status into BookStatus table
        $statusQuery = "INSERT INTO BookStatus (BookID, MemberID, Status) VALUES (?, ?, ?)";
        $statusStmt = $conn->prepare($statusQuery);
        $statusStmt->bind_param('iis', $bookId, $memberId, $status);

        if ($statusStmt->execute()) {
            $_SESSION['success_message'] = "Book and status added successfully.";
        } else {
            $_SESSION['error_message'] = "Error adding book status.";
            error_log("Error adding book status: " . $statusStmt->error); // Log error message
        }
    } else {
        $_SESSION['error_message'] = "Error adding book.";
        error_log("Error adding book to database: " . $stmt->error);  // Log error message
    }
    header('Location: admin-dashboard.php');
    exit();
}


// Handle Update Book
if (isset($_POST['action']) && $_POST['action'] == 'edit' && isset($_POST['bookId'])) {
    $bookId = $_POST['bookId'];
    $bookTitle = $_POST['bookTitle'];
    $author = $_POST['author'];
    $publisher = $_POST['publisher'];
    $language = $_POST['language'];
    $category = $_POST['category'];
    $status = $_POST['status'];
    $memberId = $_SESSION['user_id'];

    // Debug: print form data
    error_log("Editing book: $bookId, $bookTitle, $author, $publisher, $language, $category");

    // Prepare SQL query
    $query = "UPDATE Books SET BookTitle = ?, Author = ?, Publisher = ?, Language = ?, Category = ?";

    // Check if a new cover image is being uploaded
    if (isset($_FILES['cover_image']) && $_FILES['cover_image']['error'] == 0) {
        $coverImage = $_FILES['cover_image']['name'];
        $targetDir = "images-cover/";  // Images will be saved in the 'images' folder
        $targetFile = $targetDir . basename($coverImage);

        // Move the uploaded image to the 'images' directory
        if (move_uploaded_file($_FILES["cover_image"]["tmp_name"], $targetFile)) {
            error_log("Cover image uploaded successfully: $targetFile");
            $coverImageFileName = $targetFile;

            // Add CoverImagePath to the query since a new image is uploaded
            $query .= ", CoverImagePath = ?";
        } else {
            $_SESSION['error_message'] = "Error uploading cover image.";
            error_log("Error uploading cover image: " . $_FILES["cover_image"]["error"]);
            $coverImageFileName = null;
        }
    }

    // Add the WHERE condition
    $query .= " WHERE BookID = ?";

    // Prepare the statement
    $stmt = $conn->prepare($query);

    // Bind parameters
    if ($coverImageFileName) {
        // If a cover image is uploaded, bind the cover image path
        $stmt->bind_param('ssssssi', $bookTitle, $author, $publisher, $language, $category, $coverImageFileName, $bookId);
    } else {
        // If no cover image, don't bind it
        $stmt->bind_param('sssssi', $bookTitle, $author, $publisher, $language, $category, $bookId);
    }

    // Execute the statement
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Book updated successfully.";

        //can i query from book status table before insert
          //Update BookStatus Table
           $statusQuery = "INSERT INTO BookStatus (BookID, MemberID, Status) VALUES (?, ?, ?)";
            $statusStmt = $conn->prepare($statusQuery);             
             $statusStmt->bind_param('iis', $bookId, $memberId,   $status);

            if ($statusStmt->execute()) {
                $_SESSION['success_message'] .= " Status updated successfully.";
            } else {
                $_SESSION['error_message'] = "Error updating book status.";
                error_log("Error updating book status: " . $statusStmt->error);
            }


    } else {
        $_SESSION['error_message'] = "Error updating book.";
        error_log("Error updating book in database: " . $stmt->error);
    }

    // Redirect back to the admin page
    header('Location: admin-dashboard.php');
    exit();
}



// Handle Delete Book
// Check if the 'action' is set to 'delete' and the 'bookId' is provided in the POST request
if (isset($_POST['action']) && $_POST['action'] == 'delete' && isset($_POST['bookId'])) {

        $bookId = $_POST['bookId'];

// Prepare the SQL statement for updating the book status to 'deleted'
$stmt = $conn->prepare("UPDATE BookStatus SET Status = 'deleted' WHERE BookID = ?");

// Bind the parameters
$stmt->bind_param('i', $bookId); // Use 'i' for integer (book_id is typically an integer in the database)

 if ($stmt->execute()) {
        $_SESSION['success_message'] = "Book marked as deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error updated deleted marked book.";
        error_log("Error marking book as deleted " . $stmt->error);
    }

    // Redirect back to the library page
    header('Location: admin-dashboard.php');
    exit();
}

// Close the database connection
$conn->close();
?>
