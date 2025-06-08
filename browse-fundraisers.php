<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fundraiser Page</title>
    <link rel="stylesheet" href="css/browse-fundraisers.css"> 
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
            <button id="toggleBtn" class="btn secondary" onclick="toggleForm()">Sign In</button>
          </div>
        </div>
    </header>
    <header class="header1">
        <div class="hero-section">
            <h1>Save A Child Every Month</h1>
            <p>Join <strong>421,908</strong> monthly donors with Social Impact Plan and start saving needy children every month</p>
            <button>Start Giving Monthly</button>
        </div>
    </header>
    <main>
        <div class="categories">
            <h2>Categories</h2>
            <ul>
                <li><a href="browse-fundraisers.php?category=all">All Categories</a></li>
                <li><a href="browse-fundraisers.php?category=Education">Education</a></li>
                <li><a href="browse-fundraisers.php?category=Medical">Medical</a></li>
                <li><a href="browse-fundraisers.php?category=Women%20%26%20Girls">Women & Girls</a></li>
                <li><a href="browse-fundraisers.php?category=Animals">Animals</a></li>
                <li><a href="browse-fundraisers.php?category=Creative">Creative</a></li>
                <li><a href="browse-fundraisers.php?category=Food%20%26%20Hunger">Food & Hunger</a></li>
                <li><a href="browse-fundraisers.php?category=Environment">Environment</a></li>
                <li><a href="browse-fundraisers.php?category=Children">Children</a></li>
                <li><a href="browse-fundraisers.php?category=Memorial">Memorial</a></li>
                <li><a href="browse-fundraisers.php?category=Community%20Development">Community Development</a></li>
                <li><a href="browse-fundraisers.php?category=Others">Others</a></li>
            </ul>
        </div>
        <div class="fundraisers">
            <div class="filters">
                <select id="category-filter" onchange="applyFilter()">
                   <option value="all">Select Categories</option>
                    <option value="all">All Categories</option>
                    <option value="Education">Education</option>
                    <option value="Medical">Medical</option>
                    <option value="Women%20%26%20Girls">Women & Girls</option>
                    <option value="Animals">Animals</option>
                    <option value="Creative">Creative</option>
                    <option value="Food%20%26%20Hunger">Food & Hunger</option>
                    <option value="Environment">Environment</option>
                    <option value="Children">Children</option>
                    <option value="Memorial">Memorial</option>
                    <option value="Community Development">Community Development</option>
                    <option value="Others">Others</option>
                </select>
            </div>
            <div class="fundraiser-cards">
                <?php include 'fetch_fundraisers.php'; ?>
            </div>
        </div>
    </main>
         <!-- Modal (Popup) for Login and Register -->
  <div id="overlay" class="overlay"></div>
  <div id="popup" class="popup">
    <div class="popup-content">
      <!-- Close Button Inside the Popup -->
      <button class="close-btn" onclick="closeForm()">×</button>

      <!-- Login Form -->
      <div id="login-form" class="form-container">
        <h2>Login</h2>
        <form action="login.php" method="post">
          <input type="text" name="loginInput" placeholder="Username or Email" required>
          <input type="password" name="password" placeholder="Password" required>
          <button type="submit">Login</button>
        </form>
        <p class="info">Don't have an account? <a href="javascript:void(0)" onclick="toggleForm()">Sign Up</a></p>
      </div>

      <!-- Register Form -->
      <div id="register-form" class="form-container" style="display: none;">
        <h2>Register for an Account</h2>
        <form action="register.php" method="post">
          <input type="text" name="fullname" placeholder="Full Name" required>
          <input type="text" name="username" placeholder="Username" required>
          <input type="email" name="email" placeholder="Email Address" required>
          <input type="password" name="password" placeholder="Password" required>
          <input type="password" name="confirm_password" placeholder="Confirm Password" required>
          <button type="submit">Register</button>
        </form>
        <p class="info">Already have an account? <a href="javascript:void(0)" onclick="toggleForm()">Login here</a></p>
      </div>
    </div>
  </div>

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
                <p>&copy; 2025 Ketto. All Rights Reserved.</p>
            </div>
        </div>
    </footer>
    <script src="js/browse-fundraisers.js"></script>
</body>
</html>
