<?php include 'header.php'; ?>
<?php include 'header-nav.php'; ?>

<?php

// Check if user is logged in
if (!isset($_SESSION['user_name']) && empty($_SESSION['user_name'])) {
    header("Location: login.php");
    exit;
}

// Include database configuration
include 'config.php';

// Get the logged-in user's MemberID from the session
$memberId = $_SESSION['user_id'];

// SQL query to fetch book status history for the logged-in member (including borrow, return, and delete actions)
$sql = "SELECT 
            bs.BookID,
            b.BookTitle,
            b.Author,
            b.Publisher,
            b.Language,
            b.Category,
            bs.Status,
            bs.AppliedDate
     
        FROM 
            BookStatus bs
        LEFT JOIN 
            Books b ON bs.BookID = b.BookID
        WHERE 
            bs.MemberID = '$memberId'  -- Fetch records for the logged-in member
        ORDER BY 
            bs.BorrowedDate DESC";

$result = $conn->query($sql);
?>



<main class="container mt-5">
    <section class="container">
        <div class="container mt-5">
            <div class="row">
                <!-- Left menu section -->
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="index.php" class="list-group-item list-group-item-action">HOME</a>
                        <a href="member-dashboard.php" class="list-group-item list-group-item-action ">Browse & Borrow</a>
                        <a href="#" class="list-group-item list-group-item-action active">
                            Borrow History
                        </a> 
                        <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
                    </div>
                </div>

                <!-- Main content section (Right side) -->
                <div class="col-md-9">
                    <h2 class="mb-4">Member - Borrow History</h2>
                    <?php if (!empty($success_message)): ?>
                        <div class="form-group mb-3" style="color: green;">
                            <?= htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>

                    <!-- Borrow History Table -->
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>Book Title</th>
                                    <th>Action</th>
                                    <th>Applied Date</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if ($result->num_rows > 0) {
                                    while ($row = $result->fetch_assoc()) {
                                        $action = '';
                                        $actionDate = '';
                                        if ($row['Status'] == 'onloan') {
                                            $action = 'Borrowed';
                                            $actionDate = $row['AppliedDate'];
                                        } elseif ($row['Status'] == 'available') {
                                            $action = 'Returned';
                                            $actionDate = $row['AppliedDate'];
                                        } elseif ($row['Status'] == 'deleted') {
                                            $action = 'Deleted';
                                            $actionDate = $row['AppliedDate'];
                                        }

                                        // Displaying each history entry
                                        echo "<tr>
                                                <td>" . htmlspecialchars($row['BookTitle']) . "</td>
                                                <td>" . $action . "</td>
                                                <td>" . ($actionDate ? date("Y-m-d", strtotime($actionDate)) : 'N/A') . "</td>
                                              </tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='3' class='text-center'>No history found</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>

<?php
// Close the database connection
$conn->close();
?>

<?php 
echo '<pre>';
var_dump($_SESSION);
echo '</pre>';
?>

<?php include 'footer.php'; ?>
