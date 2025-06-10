<?php
include('db.php');


// Check if the user is logged in
if (isset($_SESSION['user_id']) || isset($_COOKIE['user_id'])) {
    // Redirect logged-in users to the dashboard
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Donation Page</title>
  <link rel="stylesheet" href="css/styles.css"> <!-- Link to the external CSS -->
</head>
<body>
  <header>
    <div class="container">
      <div class="logo">Crowdfunding</div>
      <nav>
        <a href="index.php">Home</a>
        <a href="browse-fundraisers.php">Browse Fundraisers</a>
        <a href="How-it-works.html">How It Works</a>
        <a href="help.html">Help</a>
      </nav>
      <div class="actions">
        <button id="toggleBtn" class="btn secondary" onclick="toggleForm()">Sign In</button> <!-- Button will toggle text based on form -->
      </div>
    </div>
  </header>

  <main>
    <div class="hero">
      <div class="hero-content">
        <h1>Medical Crowdfunding is the Best Way to Raise Money for Medical Expenses</h1>
        <p>Now with <strong>0% platform fees*</strong></p>
        <div class="success-story">
          <img src="image/Firefly human mind thoughts destroing self life 46869 (1).jpg" alt="Story Image">
          <p><strong>Dhiraj</strong> raised ₹25,00,000 for Aarohi’s Cancer Treatment in just 20 days.</p>
        </div>
      </div>

      <!-- Register Form (Initially Visible) -->
      <div id="register-form" class="form-container">
        <h2>Register for an Account</h2>
        <form action="register.php" method="post">
          <input type="text" name="fullname" placeholder="Full Name *" required>
          <input type="text" name="username" placeholder="Username *" required>
          <input type="email" name="email" placeholder="Email Address *" required>
          <input type="tel" name="phone" placeholder="Phone Number *" required>
          <input type="password" name="password" placeholder="Password *" required>
          <input type="password" name="confirm_password" placeholder="Confirm Password *" required>
          <button type="submit" class="btn">Register</button>
        </form>
        <p class="info">Already have an account? <a href="javascript:void(0)" onclick="toggleForm()">Login here</a></p>
      </div>

      <!-- Login Form (Initially Hidden) -->
      <div id="login-form" class="form-container" style="display: none;">
        <h2>Login</h2>
        <form action="login.php" method="POST">
          <input type="text" name="loginInput" placeholder="Username or Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
        </form>
        
        <p class="info">Don't have an account? <a href="javascript:void(0)" onclick="toggleForm()">Register here</a></p>
      </div>
    </div>
  </main>

  <footer>
    <div class="container">
      <div class="footer-right">
        <nav>
          <a href="privacy-policy.html">Privacy Policy</a>
          <a href="terms-and-conditions.html">Terms & Conditions</a>
          <a href="contact-us.html">Contact Us</a>
        </nav>
      </div>
      <div class="footer-left">
        <p>&copy; 2024 Ketto. All Rights Reserved.</p>
      </div>
    </div>
  </footer>
  
  <script>
    // Function to toggle between the register and login forms and change the button text
    function toggleForm() {
      const registerForm = document.getElementById('register-form');
      const loginForm = document.getElementById('login-form');
      const toggleBtn = document.getElementById('toggleBtn');

      if (registerForm.style.display === 'none') {
        // Show Register form and change button text to "Sign In"
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
        toggleBtn.textContent = 'Sign In';
      } else {
        // Show Login form and change button text to "Sign Up"
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
        toggleBtn.textContent = 'Sign Up';
      }
    }
  </script>
</body>
</html>
