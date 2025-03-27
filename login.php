
<?php include 'header.php'; ?>


<?php

// Capture the error message if it exists
$error_message = '';
if (isset($_SESSION['error']) && !empty($_SESSION['error'])) {
    $error_message = $_SESSION['error'];
    unset($_SESSION['error']); // Clear the failure message after using it
}

 if (isset($_SESSION['success']) && !empty($_SESSION['success'])) {
    $success_message = $_SESSION['success'];
   // echo  $success_message ;
    unset($_SESSION['success']);
}
?>

<?php include 'header-nav.php'; ?>
<main class="container mt-5">
    <div class="p-5 rounded">
		<div class="container">
            <h2>Login</h2>
            <p></p>
        </div>  
        <div class="container mx-auto  mt-5">
            <form id="loginform" onsubmit="return validateForm()" action="login-handler.php" method="post"  novalidate>
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
                <div class="form-group mb-3">
                    <input type="email" name="email"  class="form-control form-control-lg" id="email"  placeholder="Enter Username/Email" >
				
                    <div class="invalid-feedback" id="emailError"></div>  
                </div>
                <div class="form-group mb-3">
                    <input type="password" name="password" class="form-control form-control-lg" id="password"  placeholder="Enter Password" >
                    <i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
				    			<div class="invalid-feedback" id="passwordError"></div>  
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>
</main>


<!-- javascript for custom form validation -->
		<script>

			const togglePassword = document.getElementById('togglePassword');
			const password = document.getElementById('password');
			togglePassword.addEventListener('click', (e) => {
				const type = password.getAttribute('type') == 'password' ? 'text' : 'password';
				password.setAttribute('type', type);
				e.target.classList.toggle('fa-eye');
			});

			function validateForm() {

 
				var email = "";
				var password = "";
			
				var validity = true;
				
				var emailregex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
					
				email = document.getElementById("email");
				
				password = document.getElementById("password");
			
				// Error divs
				var emailError = document.getElementById("emailError");
				var passwordError = document.getElementById("passwordError");
			
				// Clear previous validation states
				email.classList.remove("is-invalid");
				password.classList.remove("is-invalid");

				
				// validate email
				if ( email.value == "" ) {
					emailError.innerText = "Email is required!";
					email.classList.add("is-invalid");
					validity = false;
				} else if ( !emailregex.test(email.value) ) {
					emailError.innerText = "Invalid email. Please try again.";
					email.classList.add("is-invalid");
					validity = false;
				}

				// validate password
				if ( password.value == "" ) {
					passwordError.innerText = "Password is required!";
					password.classList.add("is-invalid");
					validity = false;
				} else if ( !passwordregex.test(password.value) ) {
					passwordError.innerText = "Your password must be 8-15 characters with at least 1 uppercase, 1 lowercase, 1 number, and 1 special character.";
					password.classList.add("is-invalid");
					validity = false;
				}

				return validity;
			}	
		</script>		


<?php include 'footer.php'; ?>
<?php 
