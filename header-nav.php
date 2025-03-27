
<?php
// Get the current file name
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
   
    <!--Bootstrap CSS -->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH"
      crossorigin="anonymous"
    />
    <!--Custom CSS -->
    <link rel="stylesheet" href="css/styles.css" />
    <!--Bootstrap JS - need to add defer-->
    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
      crossorigin="anonymous"
      defer
    ></script>
    <!-- declar in head need to put defer to ensure it not break-->
    <script
      src="https://kit.fontawesome.com/2e7148a792.js"
      crossorigin="anonymous"
      defer
    ></script>
    <title>LMS</title>

</head>
<body>
    <header> 
      <nav class="navbar navbar-expand-lg navbar-dark fixed-top bg-dark">
          <div class="container">
                    <a class="navbar-brand"  href="index.php">LMS Australian University</a>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
                      <span class="navbar-toggler-icon"></span>
                    </button>
                    <div class="collapse navbar-collapse" id="navbarCollapse">
                      <ul class="navbar-nav mb-2 mb-md-0  ms-auto">
                          <li class="nav-item">
                              <a class="nav-link <?= $current_page == 'index.php' ? 'active' : '' ?>" href="index.php">Home</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link <?= $current_page == 'member-dashboard.php' ? 'active' : '' ?>" href="member-dashboard.php">Browse Books</a>
                          </li>
                          <li class="nav-item">
                              <a class="nav-link <?= $current_page == 'admin-dashboard.php' ? 'active' : '' ?>" href="admin-dashboard.php">Edit/Return Books</a>
                          </li>
                          <?php if (!empty($_SESSION['user_name'])): ?>
                              <li class="nav-item">
                                  <a class="nav-link">Hello, <?= htmlspecialchars($_SESSION['user_name']); ?></a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link" href="logout.php">Logout</a>
                              </li>
                          <?php else: ?>
                              <li class="nav-item">
                                  <a class="nav-link <?= $current_page == 'signup.php' ? 'active' : '' ?>" href="signup.php">Sign Up</a>
                              </li>
                              <li class="nav-item">
                                  <a class="nav-link <?= $current_page == 'login.php' ? 'active' : '' ?>" href="login.php">Login</a>
                              </li>
                          <?php endif; ?>
                        <!--li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Disabled</a>
                        </!--li -->
                      </ul>
                    </div>
           </div>
       </nav>

    </header>  
    