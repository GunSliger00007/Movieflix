<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin Login</title>
  
  <link rel="stylesheet" href="styles.css">
  <style>
    .error {
      color: red;
      font-size: 0.9em;
      display: none;
    }
  </style>
</head>
<body>

<div id="login-form-wrap">
  <h2>Login</h2>
  <form id="login-form" method="post" action="login.php" onsubmit="return validateForm()">
    <p>
      <input type="email" id="email" name="email" placeholder="Email Address" required>
      <i class="validation"><span></span><span></span></i>
      <span id="email-error" class="error">Please enter a valid email address.</span>
    </p>
    <p>
      <input type="password" id="password" name="password" placeholder="Password" required>
      <i class="validation"><span></span><span></span></i>
      <span id="password-error" class="error">
        Password must be at least 8 characters long, contain an uppercase letter, a lowercase letter, a number, and a special character.
      </span>
    </p>
    <p>
      <input type="submit" id="login" value="Login">
    </p>
  </form>
  <div id="create-account-wrap">
    
  </div>
</div>

<script>
// Function to validate the form
function validateForm() {
  const email = document.getElementById("email").value;
  const password = document.getElementById("password").value;
  let isValid = true;

  // Email format validation using regex
  const emailPattern = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
  const emailError = document.getElementById("email-error");
  if (!emailPattern.test(email)) {
    emailError.style.display = "block";
    isValid = false;
  } else {
    emailError.style.display = "none";
  }

  // Password validation using JavaScript regex
  const passwordError = document.getElementById("password-error");
  const passwordPattern = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[@#$%^&+=]).{8,}/;
  if (!passwordPattern.test(password)) {
    passwordError.style.display = "block";
    isValid = false;
  } else {
    passwordError.style.display = "none";
  }

  return isValid; // If all validation passes, the form will be submitted
}
</script>

</body>
</html>
