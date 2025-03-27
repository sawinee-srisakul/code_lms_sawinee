
<?php include 'header.php'; ?>
<?php include 'header-nav.php'; ?>


<main class="container mt-5">

     <div class="p-5 rounded">
			<div class="container">
				<h2>Create Account</h2>
				<p></p>
			</div>  
			<div class="container mx-auto">
				<!-- add content here  onsubmit="return validateForm()" -->
				<form action="signup-handler.php" onsubmit="return validateForm()"  method="post" novalidate>
			
					<div class="form-group mb-3">
						<input type="text" name="fname" class="form-control form-control-lg" id="fname"  placeholder="First Name" >
						<div class="invalid-feedback" id="firstNameError"></div>  
					</div>
					<div class="form-group mb-3">
						<input type="text"  name="lname" class="form-control form-control-lg" id="lname" placeholder="Last Name" >
						<div class="invalid-feedback" id="lastNameError"></div>  
					</div>
					<div class="form-group mb-3">
						<input type="email" name="email"  class="form-control form-control-lg" id="email"  placeholder="Email address ( Member username )" >
						<div class="invalid-feedback" id="emailError"></div>  
					</div>
					<div class="form-group mb-3">
						<input type="password" name="password" class="form-control form-control-lg" id="password"  placeholder="Password" >
						<i class="fa-solid fa-eye-slash toggle-password" id="togglePassword"></i>
						<div class="invalid-feedback" id="passwordError"></div>  
					</div>	
					<div class="form-group mb-3">
						<input type="password" name="confirm_password"  class="form-control form-control-lg" id="confirm_password"  placeholder="Re-type password">
						<i class="fa-solid fa-eye-slash toggle-password" id="toggleConfirmPassword"></i>
						<div class="invalid-feedback" id="confirmPasswordError"></div>  
					</div>
					<div class="form-group mb-3">
		
							<select id="role" name="role" class="custom-select form-control">
									<option value="member">Member</option>
									<option value="admin">Admin</option>
							</select>

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

			const toggleConfirmPassword = document.getElementById('toggleConfirmPassword');
			const confirmPassword = document.getElementById('confirm_password');
			toggleConfirmPassword.addEventListener('click', (e) => {
				const type = confirmPassword.getAttribute('type') == 'password' ? 'text' : 'password';
				confirmPassword.setAttribute('type', type);
				e.target.classList.toggle('fa-eye');
			});

			function validateForm() {
				var email = "";
				var firstName = "";
				var lastName = "";
				var password = "";
				var confirmPassword = "";
				var validity = true;
				
				var nameregex =  /^[a-zA-Z]{1,20}$/;
				var emailregex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
				var passwordregex = /^(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[^a-zA-Z0-9])(?!.*\s).{8,15}$/;
					
				email = document.getElementById("email");
				firstName = document.getElementById("fname");
				lastName = document.getElementById("lname");
				password = document.getElementById("password");
				confirmPassword = document.getElementById("confirm_password");

				// Error divs
				var firstNameError = document.getElementById("firstNameError");
				var lastNameError = document.getElementById("lastNameError");
				var emailError = document.getElementById("emailError");
				var passwordError = document.getElementById("passwordError");
				var confirmPasswordError = document.getElementById("confirmPasswordError");

				// Clear previous validation states
				email.classList.remove("is-invalid");
				firstName.classList.remove("is-invalid");
				lastName.classList.remove("is-invalid");
				password.classList.remove("is-invalid");
				confirmPassword.classList.remove("is-invalid");
				
				// validate first name
				if ( firstName.value == "" ) {
					firstNameError.innerText = "First Name is required!";
					firstName.classList.add("is-invalid");
					validity = false;
				} else if ( !nameregex.test(firstName.value) ) {
					firstNameError.innerText = "First Name must contain only letters and be no more than 20 characters long.";
					firstName.classList.add("is-invalid");
					validity = false;
				}

				// validate last name
				if ( lastName.value == "" ) {
					lastNameError.innerText = "Last Name is required!";
					lastName.classList.add("is-invalid");
					validity = false;
				} else if ( !nameregex.test(lastName.value) ) {
					lastNameError.innerText = "Last Name must contain only letters and be no more than 20 characters long.";
					lastName.classList.add("is-invalid");
					validity = false;
				}

				// validate email
				if ( email.value == "" ) {
					emailError.innerText = "Email is required!";
					email.classList.add("is-invalid");
					validity = false;
				} else if ( !emailregex.test(email.value) ) {
					emailError.innerText = "Invalid email format. Please try again, EXAMPLE ''JAMES.SMITH@EMAIL.COM''.";
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

				// validate confirm password
				if ( confirmPassword.value == "" ) {
					confirmPasswordError.innerText = "Password is required!";
					confirmPassword.classList.add("is-invalid");
					validity = false;
				} else if ( password.value !== confirmPassword.value ) {
					confirmPasswordError.innerText = "Password do not match! Please try again.";
					confirmPassword.classList.add("is-invalid");
					validity = false;
				}
						
				return validity;
			}	
		</script>		


<?php include 'footer.php'; ?>