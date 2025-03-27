<?php
//session_start(); // start the session already in header


// Capture and display error message if it exists
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    echo "<div class='alert alert-danger'>" . $_SESSION['error'] . "</div>";
    unset($_SESSION['error']); // Clear the error message after displaying it
}

// Capture and display success message if it exists
if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
 //   echo "<div class='alert alert-success'>" . $_SESSION['success'] . "</div>";
    unset($_SESSION['success']);
}
?>

<?php include 'header.php'; ?>
<?php include 'header-nav.php'; ?>

 <!-- Banner Section -->
<section class="banner mt-5">
    <img src="images/banner-library.jpg" alt="Library Banner">
    <h1  class="display-4">Welcome to Australian University Library Management System</h1>
</section>
<main class="container mt-5">

  <section class="container my-5">
      <div class="row text-center">
          <div class="col-md-4">
              <div class="card shadow-sm">
                  <img src="images/book-search.jpg" class="card-img-top" alt="Search Books">
                  <div class="card-body">
                      <h5 class="card-title">Search Books</h5>
                      <p class="card-text">Easily find the books you need with our advanced search feature.</p>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="card shadow-sm">
                  <img src="images/manage-borrow.jpg" class="card-img-top" alt="Manage Borrowing">
                  <div class="card-body">
                      <h5 class="card-title">Manage Borrowing</h5>
                      <p class="card-text">Keep track of your borrowed books and due dates seamlessly.</p>
                  </div>
              </div>
          </div>
          <div class="col-md-4">
              <div class="card shadow-sm">
                  <img src="images/e-resources.jpg" class="card-img-top" alt="Access E-Resources">
                  <div class="card-body">
                      <h5 class="card-title">Access E-Resources</h5>
                      <p class="card-text">Explore our collection of e-books, journals, and research papers.</p>
                  </div>
              </div>
          </div>
      </div>
  </section>
</main>


	

<?php include 'footer.php'; ?>