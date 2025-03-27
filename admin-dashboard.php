
<?php include 'header.php'; ?>

<?php
// Only admin can access CMS
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    echo '<div class="access-denied">Access denied. You must be an admin to access this page.</div>';
    exit();
}

// Capture the error message if it exists
$error_message = '';
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); // Clear the failure message after using it
}

// Include database configuration
include 'config.php';


// Default search query (empty)
$searchTerm = '';
$category = '';

// Check if the form has been submitted
if (isset($_POST['search'])) {
    $searchTerm = $_POST['search']; 
}

if (isset($_POST['category'])) {
    $category = $_POST['category'];
}

$sql = "SELECT 
    b.BookID, 
    b.BookTitle, 
    b.Author, 
    b.Publisher, 
    b.Language, 
    b.Category, 
    b.CoverImagePath, 
    IFNULL(bs.Status, 'available') AS Status
FROM Books b
LEFT JOIN (
    -- Get the latest AppliedDate for each book
    SELECT BookID, MAX(AppliedDate) AS MaxAppliedDate
    FROM BookStatus
    GROUP BY BookID
) latest_status ON b.BookID = latest_status.BookID
LEFT JOIN BookStatus bs ON b.BookID = bs.BookID 
    AND bs.AppliedDate = latest_status.MaxAppliedDate
WHERE 1=1";  // This is a placeholder for the search conditions

// Add search conditions if there is a search term
if ($searchTerm != '') {
    $sql .= " AND (b.BookTitle LIKE '%" . $conn->real_escape_string($searchTerm) . "%' 
              OR b.Publisher LIKE '%" . $conn->real_escape_string($searchTerm). "%' 
              OR b.Author LIKE '%" . $conn->real_escape_string($searchTerm) . "%')";
}

// Add category condition if selected
if ($category != '') {
    $sql .= " AND b.Category = '" . $conn->real_escape_string($category) . "'";
}

// Add ORDER BY clause to sort by BookTitle A-Z
$sql .= " ORDER BY b.BookTitle ASC";

$result = $conn->query($sql);

?>



<?php include 'header-nav.php'; ?>
<main class="container mt-5">
    <section>
        <div class="container mt-5">
            <div class="row">
                <!-- Left menu section -->
                <div class="col-md-3">
                    <div class="list-group">
                        <a href="index.php" class="list-group-item list-group-item-action">HOME</a>
                         <a href="#" 
                            class="list-group-item list-group-item-action" 
                            data-toggle="modal" 
                            data-target="#bookModal">
                                Add New Book
                            </a>
                            <a href="#" class="list-group-item list-group-item-action active">Edit / Return / Delete Book</a>
                      
                         <a href="logout.php" class="list-group-item list-group-item-action">Logout</a> 
                    </div>
                </div>
                
                <!-- Main content section (Right side) -->
                <div class="col-md-9">
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

                    <h2>Admin - Manage Books</h2>
                    <!-- Search Form -->
                    <form method="POST" class="form-inline mb-3">
                         <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input type="text"  id="searchInput" class="form-control" name="search" placeholder="Search Books" value="<?php echo htmlspecialchars($searchTerm); ?>">
                                    </div>
                                </div> 
                                <div class="col-md-4">
                                   <div class="form-group">
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
                                <div class="col-md-2 d-flex align-items-end"> <!-- Flexbox to align button at the bottom -->
                                    <div class="form-group w-100">
                                        <button type="submit" class="btn btn-primary ml-2">Search</button>
                                    </div>
                              </div>
                            </div>

                    </form>
                    <!-- Button to Open the Add Book Form Modal -->
                    <div class="form-group">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#bookModal">
                            Add New Book
                        </button>
                    </div>

                    <!-- Add Book Form Modal -->
                    <div class="modal fade" id="bookModal" tabindex="-1" role="dialog" aria-labelledby="bookModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document"> 
                            <div class="modal-content">
                                 <!-- Modal Header -->
                            <div class="modal-header">
                                <h5 class="modal-title" id="bookModalLabel">Add New Book</h5>
                                <button type="button" class="close  ms-auto " data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                                <!--div class="modal-header">
                                    <h5 class="modal-title" id="bookModalLabel">Add New Book</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </!--div-->
                                <div class="modal-body">
                            
                                    <form id="bookForm" method="POST" action="admin-dashboard-handler.php" enctype="multipart/form-data" novalidate>
                                        <input type="hidden" id="action" name="action" value="add"> <!-- This is the action field -->
                                        <input type="hidden" id="bookId" name="bookId"> <!-- Hidden field for Book ID -->

                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group mb-3">
                                                    <label for="cover_image">Book Cover</label>
                                                    <input type="file" class="form-control-file" id="cover_image" name="cover_image">
                                                    <img id="currentCoverImage" src="#" alt="Current Cover" style="max-width: 100px; display: none;">



                                
                                                </div>
                                            </div>
                                            <div class="col-md-9">
                                                <div class="form-group mb-3">
                                                    <label for="bookTitle">Book Title</label>
                                                    <input type="text" class="form-control" id="bookTitle" name="bookTitle" minlength="3" maxlength="30" pattern="[A-Za-z0-9\s]+" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="author">Author</label>
                                                    <input type="text" class="form-control" id="author" name="author" minlength="3" maxlength="30" pattern="[A-Za-z0-9\s]+" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="publisher">Publisher</label>
                                                    <input type="text" class="form-control" id="publisher" name="publisher" minlength="3" maxlength="30" pattern="[A-Za-z0-9\s]+" required>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="language">Language</label>
                                                    <select id="language" class="form-control" name="language" required>
                                                        <option value="english" selected>English</option>
                                                        <option value="french">French</option>
                                                        <option value="german">German</option>
                                                        <option value="mandarin">Mandarin</option>
                                                        <option value="japanese">Japanese</option>
                                                        <option value="russian">Russian</option>
                                                        <option value="other">Other</option>
                                                    </select>
                                                </div>
                                                <div class="form-group mb-3">
                                                    <label for="category">Category</label>
                                                    <select id="category" class="form-control" name="category" required>
                                                        <option value="fiction" selected>Fiction</option>
                                                        <option value="nonfiction">NonFiction</option>
                                                        <option value="reference">Referrence</option>
                                                    </select>
                                                
                                                </div>

                                                <div class="form-group mb-3">
                                                    <label for="status">Borrow Status</label>
                                                    <select id="status" class="form-control" name="status" required>
                                                        <option value="available" selected>Available</option>
                                                        <option value="onloan">On loan</option>
                                                        <option value="deleted">Delete</option>
                                                    </select>
                                                </div>
                                                
                                                <button type="submit" class="btn btn-primary"  id="modalSubmitButton">Add Book</button>
                                            </div>
                                        </div> 
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <hr>

                    <!-- List of Books -->
                    <h4>Books List</h4>
                    <div class="row">
                        <?php
                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                        ?>
                             <div class="col-lg-4 col-md-6 col-12 mb-4">
                                <div class="card h-100 position-relative">
                                    <!-- Image with Edit Button -->
                                    <div class="position-relative">
                                        <img src="<?php echo htmlspecialchars($row['CoverImagePath']); ?>" 
                                            class="card-img-top" 
                                            alt="Book Cover" 
                                            style="height: 300px; object-fit: cover;">
                                            
                                        <button type="button" 
                                                class="btn btn-primary position-absolute" 
                                                style="top: 10px; right: 10px;" 
                                                data-toggle="modal"
                                                data-action="edit"
                                                data-target="#bookModal"
                                                data-id="<?php echo $row['BookID']; ?>"
                                                data-title="<?php echo htmlspecialchars($row['BookTitle']); ?>"
                                                data-author="<?php echo htmlspecialchars($row['Author']); ?>"
                                                data-publisher="<?php echo htmlspecialchars($row['Publisher']); ?>"
                                                data-language="<?php echo htmlspecialchars($row['Language']); ?>"
                                                data-category="<?php echo htmlspecialchars($row['Category']); ?>"
                                                data-cover="<?php echo htmlspecialchars($row['CoverImagePath']); ?>"
                                                data-status="<?php echo htmlspecialchars($row['Status']); ?>">
                                            Edit
                                        </button>
                                    </div>

                                    <!-- Card Body -->
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title"><?php echo htmlspecialchars($row['BookTitle']); ?></h5>
                                        <p class="card-text"><strong>Author:</strong> <?php echo htmlspecialchars($row['Author']); ?></p>
                                        <p class="card-text"><strong>Publisher:</strong> <?php echo htmlspecialchars($row['Publisher']); ?></p>
                                        <p class="card-text"><strong>Language:</strong> <?php echo htmlspecialchars($row['Language']); ?></p>
                                        <p class="card-text"><strong>Category:</strong> <?php echo htmlspecialchars($row['Category']); ?></p>
                                        <p class="card-text"><strong>Borrow Status:</strong> <?php echo htmlspecialchars($row['Status']); ?></p>
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
                </div> <!--end Main content section (Right side) -->             

            </div> <!-- end row -->
    
        </div> <!-- end container -->

    </section>

</main>



    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <?php include 'footer.php'; ?>



    <script>
        document.addEventListener("DOMContentLoaded", function () {
        const bookModal = document.getElementById("bookModal");
        const modalTitle = document.getElementById("bookModalLabel");
        const actionInput = document.getElementById("action");
        const bookForm = document.getElementById("bookForm");
        const modalSubmitButton = document.getElementById("modalSubmitButton");

        


        // Add/Edit button click handler
        $("#bookModal").on("show.bs.modal", function (event) {
            const button = $(event.relatedTarget); // Button that triggered the modal
            const action = button.data("action"); // Get action (add or edit)

            // Reset form fields
            bookForm.reset();
            document.getElementById("bookId").value = "";

            if (action === "edit") {
                // Populate form fields with book data
                modalTitle.textContent = "Edit Book";
                modalSubmitButton.textContent = "Save Changes";
                actionInput.value = "edit";

                const coverImage = button.data("cover"); // Existing cover image URL
                const currentCoverImage = document.getElementById("currentCoverImage");

                if (coverImage) {
                    currentCoverImage.src = coverImage;
                    currentCoverImage.style.display = "block";
                } else {
                    currentCoverImage.style.display = "none";
                }
                

                // Populate fields with data attributes
                document.getElementById("bookId").value = button.data("id");
                document.getElementById("bookTitle").value = button.data("title");
                document.getElementById("author").value = button.data("author");
                document.getElementById("publisher").value = button.data("publisher");
                document.getElementById("language").value = button.data("language");
                document.getElementById("category").value = button.data("category");
                document.getElementById("status").value = button.data("status");

               
                
            } else {
                // Set up for adding a new book
                modalTitle.textContent = "Add New Book";
                modalSubmitButton.textContent = "Add Book";
                actionInput.value = "add";
                   
                document.getElementById("currentCoverImage").style.display = "none";
            }


           document.getElementById('cover_image').addEventListener('change', function(event) {
            const fileInput = event.target; // Reference to the file input element
            const previewImage = document.getElementById('currentCoverImage'); // Reference to the img element

            // Check if there's a file selected
            if (fileInput.files && fileInput.files[0]) {
                const reader = new FileReader(); // Create a new FileReader object

                // Set up a callback to run when the file is read
                reader.onload = function(e) {
                    previewImage.src = e.target.result; // Set the src of the img element to the file's data
                    previewImage.style.display = 'block'; // Make the image visible
                };

                // Read the file as a Data URL (base64 encoded)
                reader.readAsDataURL(fileInput.files[0]);
            } else {
                alert('test look at me');
                previewImage.src = ''; // Clear the src if no file is selected
                previewImage.style.display = 'none'; // Hide the image
            }
        });


 // Handle form submission logic with validation
        bookForm.onsubmit = function (event) {
            const coverImageInput = document.getElementById('cover_image');
            const coverImageUrl = document.getElementById("currentCoverImage").src;

            // Get the values from the input fields
            const bookTitle = document.getElementById("bookTitle").value;
            const author = document.getElementById("author").value;
            const publisher = document.getElementById("publisher").value;

            // Validate Book Title, Author, Publisher length (max 30 characters)
            if (bookTitle.length < 3 || bookTitle.length > 30) {
                event.preventDefault();
                alert('Book Title must be 30 characters or less.');
                return;
            }
        
             if (author.length < 3 || author.length > 30) {
                event.preventDefault();
                alert('Author name must be 30 characters or less.');
                return;
            }
             if (publisher.length < 3 || publisher.length > 30) {
                event.preventDefault();
                alert('Publisher name must be 30 characters or less.');
                return;
            }

            // Validate cover image: Ensure user uploads one or that an existing cover is present for edit
            if (!coverImageInput.files.length && !coverImageUrl) {
                event.preventDefault();
                alert('Please upload a cover image. You cannot submit without one.');
                return;
            }
        };

    });
    
});


</script>

       <!-- Optional: Script to clear search results when input is cleared -->
<script>
    const searchInput = document.getElementById('searchInput');
    const searchForm = searchInput.closest('form'); // Get the form element

    // Listen for the input event (when the user types in the search box)
    searchInput.addEventListener('input', function() {
        // If the search input is cleared, reset the search results
        if (searchInput.value.trim() === '') {
            // Automatically submit the form or reset results
            searchForm.submit(); // This will trigger the form submission without the search query
        }
    });
</script>



<?php
// Close the database connection
$conn->close();
?>


