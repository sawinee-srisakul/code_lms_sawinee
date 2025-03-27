
<?php include 'header.php'; ?>


<?php
// Only admin can access CMS
if (empty($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
   // echo '<div class="access-denied">Access denied. You must login to access this page.</div>';

    }
    
if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
    unset($_SESSION['success']);
}
?>


<?php 
// Include database configuration
include 'config.php';

// Initialize search term variable
$searchTerm = "";
$category = "";

if (isset($_POST['search'])) {
    $searchTerm = $_POST['search'];
}

if (isset($_POST['category'])) {
    $category = $_POST['category'];
}


// SQL query include search functionality
$sql = "SELECT 
            b.BookID, 
            b.BookTitle, 
            b.Author, 
            b.Publisher, 
            b.Language, 
            b.Category, 
            b.CoverImagePath, 
            COALESCE(bs.Status, 'available') AS Status, 
            bs.BorrowedDate, 
            bs.ReturnDueDate
        FROM 
            Books b
        LEFT JOIN (
            SELECT 
                BookID, 
                MAX(AppliedDate) AS MaxAppliedDate  -- Get the latest applied date (including available status)
            FROM 
                BookStatus
            GROUP BY 
                BookID
        ) latest_status ON b.BookID = latest_status.BookID
        LEFT JOIN BookStatus bs ON b.BookID = bs.BookID 
            AND bs.AppliedDate = latest_status.MaxAppliedDate
        WHERE 
            (bs.Status != 'deleted' OR bs.Status IS NULL)
            AND (b.BookTitle LIKE '%$searchTerm%' 
                OR b.Author LIKE '%$searchTerm%' 
                OR b.Publisher LIKE '%$searchTerm%' 
                OR ('$searchTerm' = ''))
            AND ('$category' = '' OR b.Category = '$category') -- Category filter
        ORDER BY 
            b.BookTitle";

$result = $conn->query($sql);

?>


<?php include 'header-nav.php'; ?>
<main class="container mt-5">

    <section class="container">
        <div class="container mt-5">

            <div class="row">
                <!-- Left menu section -->
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="index.php" class="list-group-item list-group-item-action">HOME</a>
                        <a href="#" class="list-group-item list-group-item-action active">Browse & Borrow</a>
                        <a href="member-history-dashboard.php" class="list-group-item list-group-item-action" >
                            Borrow History
                        </a> 
                        <a href="logout.php" class="list-group-item list-group-item-action">Logout</a>
                    </div>
                </div>
                
                <!-- Main content section (Right side) -->
                <div class="col-md-9">

                    <h2 class="mb-4">Member - Browse and Borrow</h2>
                    <?php if (!empty($success_message)): ?>
                        <div class="form-group mb-3" style="color: green;">
                            <?= htmlspecialchars($success_message); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($error_message)): ?>
                    <div class="form-group mb-3" style="color: red;">
                        <?= htmlspecialchars($error_message); ?>
                    </div>
                     <?php endif; ?>

                    <div class="mb-4">

                        <!-- Search Form -->
                        <form method="POST" class="mb-5">
                            <div class="row">
                                <!-- Search input -->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="search">Search Books:</label>
                                        <input 
                                            type="text" 
                                            class="form-control" 
                                            id="search" 
                                            name="search" 
                                            value="<?php echo htmlspecialchars($searchTerm); ?>" 
                                            placeholder="Search by title, author, or publisher">
                                    </div>
                                </div>
                                <!-- Category dropdown -->
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="categoryDropdown">Category:</label>
                                        <select 
                                            id="categoryDropdown" 
                                            class="form-control" 
                                            name="category">
                                            <option value="">All Categories</option>
                                            <option value="fiction" <?php echo isset($_POST['category']) && $_POST['category'] == 'fiction' ? 'selected' : ''; ?>>Fiction</option>
                                            <option value="nonfiction" <?php echo isset($_POST['category']) && $_POST['category'] == 'nonfiction' ? 'selected' : ''; ?>>Nonfiction</option>
                                            <option value="reference" <?php echo isset($_POST['category']) && $_POST['category'] == 'reference' ? 'selected' : ''; ?>>Reference</option>
                                        </select>
                                    </div>
                                </div>
                                <!-- Search button -->
                                <div class="col-md-2 d-flex align-items-end">
                                    <button type="submit" class="btn btn-primary w-100">Search</button>
                                </div>
                            </div>
                        </form>

                    </div>
                 

                    <?php
                    // Display success message if a book was borrowed
                    if (isset($_SESSION['borrow_success'])) {
                        echo "<div class='alert alert-success mt-4'>" . $_SESSION['borrow_success'] . "</div>";
                        unset($_SESSION['borrow_success']);
                    }
                    ?>

                    <!-- List of Books -->
                    <h4>Books List</h4>

                    <div class="row">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                // Determine if the book is available or borrowed
                                $isAvailable = ($row['Status'] === 'available');
                                $borrowButtonText = $isAvailable ? "Borrow" : "Not Available";
                                $buttonClass = $isAvailable ? "btn-primary" : "btn-secondary disabled";

                                // Calculate the available date if the book is currently borrowed
                                $availableDateMessage = '';
                                if (!$isAvailable && !empty($row['ReturnDueDate'])) {
                                    $returnDueDate = new DateTime($row['ReturnDueDate']);
                                    $returnDueDate->modify('+1 day');
                                    $availableDateMessage = "Available on: " . $returnDueDate->format('Y-m-d');
                                }
                        ?>
                            <!--div class="col-md-4 mb-4" --> 
                             <div class="col-lg-4 col-md-6 col-12 mb-4">
                                <div class="card h-100">
                                    <img src="<?php echo htmlspecialchars($row['CoverImagePath']); ?>" class="card-img-top" alt="Book Cover" style="height: 300px; object-fit: cover;">
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['BookTitle']); ?></h5>
                                        <p class="card-text"><strong>Author:</strong> <?php echo htmlspecialchars($row['Author']); ?></p>
                                        <p class="card-text"><strong>Publisher:</strong> <?php echo htmlspecialchars($row['Publisher']); ?></p>
                                        <p class="card-text"><strong>Language:</strong> <?php echo htmlspecialchars($row['Language']); ?></p>
                                        <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($row['Category']); ?></p>
                                         <p class="card-text"><strong>Status:</strong> <?php echo htmlspecialchars($row['Status']); ?></p>
                                       
                                        <?php if (!$isAvailable): ?>
                                            <p class="card-text text-danger"><?php echo $availableDateMessage; ?></p>
                                        <?php endif; ?>
                                        
                                    <form method="POST" action="member-dashboard-handler.php">
                                            <input type="hidden" name="book_id" value="<?php echo $row['BookID']; ?>">
                                            <input type="hidden" name="member_id" value="<?php echo $_SESSION['user_id']; ?>">
                                            <button class="mt-auto btn <?php echo $buttonClass; ?>" type="submit" <?php echo !$isAvailable ? 'disabled' : ''; ?>>
                                                <?php echo $borrowButtonText; ?>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php
                            }
                        } else {
                            echo "<p class='text-center w-100'>No books available</p>";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </section>



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
   
