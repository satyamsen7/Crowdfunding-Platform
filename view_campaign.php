<?php
include 'db.php'; // Include database connection

// Retrieve the campaign link from the URL
if (isset($_GET['campaign_link'])) {
    $campaign_link = $_GET['campaign_link'];

    // Fetch the campaign details
    $stmt = $conn->prepare("SELECT * FROM fundraisers WHERE campaign_link = ?");
    $stmt->bind_param("s", $campaign_link);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fundraiser = $result->fetch_assoc();

        // Fetch the total amount donated so far for this campaign
        $stmt_donations = $conn->prepare("SELECT SUM(amount) as total_collected FROM donations WHERE campaign_link = ?");
        $stmt_donations->bind_param("s", $campaign_link);
        $stmt_donations->execute();
        $result_donations = $stmt_donations->get_result();
        $donation_data = $result_donations->fetch_assoc();
        $total_collected = $donation_data['total_collected'] ?? 0; // If null, set it to 0

        // Calculate the progress percentage and remaining amount
        $amount_needed = $fundraiser['amount'];
        $remaining_amount = $amount_needed - $total_collected;
        $progress_percentage = ($total_collected / $amount_needed) * 100;

        // Create the campaign URL (adjust to your actual domain)
        $campaign_url = "http://localhost/crowdfunding-website/view_campaign.php?campaign_link=" . urlencode($fundraiser['campaign_link']);
        ?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Campaign: <?php echo htmlspecialchars($fundraiser['person_name']); ?></title>
  <link rel="stylesheet" href="css/styles.css"> <!-- Link to external CSS -->
  <style> body { font-family: 'Arial', sans-serif; background-color: #f4f4f4; margin: 0; padding: 0; } .campaign-container { width: 100%; max-width: 800px; margin: 20px auto; background-color: #ffffff; border-radius: 8px; box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1); overflow: hidden; display: flex; flex-direction: column; } .campaign-photo img { width: 100%; height: auto; max-height: 400px; object-fit: cover; } .campaign-content { padding: 20px; } .campaign-content h1 { font-size: 28px; margin: 0; color: #333; } .campaign-content p { margin: 10px 0; font-size: 16px; color: #666; } .progress-bar-container { width: 100%; background-color: #eee; border-radius: 30px; overflow: hidden; margin: 20px 0; } .progress-bar-fill { height: 20px; background-color: #4caf50; border-radius: 30px; width: <?php echo round($progress_percentage); ?>%; text-align: center; color: white; line-height: 20px; font-size: 14px; } .donate-section { margin-top: 20px; text-align: center; } .donate-section input[type="number"] { width: 80%; padding: 12px; margin-bottom: 10px; border: 1px solid #ccc; border-radius: 5px; text-align: center; font-size: 16px; } .donate-section button { padding: 12px 20px; background-color: #007bff; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 16px; transition: background-color 0.3s; } .donate-section button:hover { background-color: #0056b3; } .share-section { margin-top: 20px; text-align: center; } .share-section button, .share-section a { background-color: #e0e0e0; color: black; padding: 10px 15px; margin: 5px; border: none; cursor: pointer; border-radius: 5px; font-size: 14px; text-decoration: none; transition: background-color 0.3s; } .share-section button:hover, .share-section a:hover { background-color: #ccc; } #error_message { color: red; font-size: 14px; } @media (max-width: 600px) { .campaign-content h1 { font-size: 24px; } .donate-section input[type="number"] { width: 90%; } .donate-section button { width: 90%; padding: 12px; } .share-section button, .share-section a { width: 90%; } .share-section { display: flex; flex-direction: column; align-items: center; } } @media (max-width: 400px) { .campaign-content p { font-size: 14px; } } </style>
  <style>
    /* General Reset */
    * {
      margin: 0;
      padding: 0;
      box-sizing: border-box;
    }

    body {
      font-family: Arial, sans-serif;
      line-height: 1.6;
      background-color: #f4f4f4; /* Light background for the entire page */
      color: #333;
    }

    header {
      background: white;
      padding: 10px 20px;
      border-bottom: 1px solid #ddd;
    }

    .popup {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
        z-index: 1000;
      }
  
      .popup-content {
        background: white;
        padding: 20px;
        border-radius: 8px;
        width: 400px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        position: relative; /* For positioning the close button */
      }
  
      .popup-content h2 {
        margin-bottom: 20px;
        color: #333;
      }
  
      .form-container {
        width: 100%;
      }
  
      .form-container input,
      .form-container button {
        width: 100%;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid #ddd;
        border-radius: 5px;
      }
  
      .form-container button {
        background-color: #1abc9c;
        color: white;
        cursor: pointer;
      }
  
      .close-btn {
        position: absolute;
        top: 10px;
        right: 10px;
        background: transparent;
        border: none;
        font-size: 20px;
        color: #333;
        cursor: pointer;
      }
  
      /* Background overlay effect */
      .overlay {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.7);
        z-index: 999;
      }
    .container {
      display: flex;
      align-items: center;
    }

    .logo {
      font-size: 24px;
      font-weight: bold;
      color: #1abc9c;
    }

    nav a {
      margin: 0 10px;
      text-decoration: none;
      color: #333;
      transition: color 0.3s ease;
    }

    nav a:hover {
      color: #1abc9c; /* Hover effect */
    }

    footer {
      background: white;
      color: #333;
      padding: 30px 20px;
      border-top: 1px solid #ddd;
      text-align: center;
    }

    footer .container {
      display: flex;
      justify-content: space-between;
      align-items: center;
      max-width: 1200px;
      margin: 0 auto;
    }

    footer .footer-left p {
      margin: 0;
      font-size: 14px;
      color: #888;
    }

    footer .footer-right {
      display: flex;
      flex-direction: column;
      align-items: flex-end;
    }

    footer .footer-right nav {
      margin-bottom: 10px;
    }

    footer .footer-right a {
      text-decoration: none;
      color: #333;
      margin: 0 10px;
      font-size: 14px;
      transition: color 0.3s ease;
    }

    footer .footer-right a:hover {
      color: #1abc9c; /* Highlight color on hover */
    }

    /* Mobile-Responsive Footer */
    @media (max-width: 768px) {
      footer .container {
        flex-direction: column;
        text-align: center;
      }

      footer .footer-right {
        align-items: center;
        margin-top: 20px;
      }

      footer .footer-right nav {
        margin-bottom: 15px;
      }
    }
  </style>
</head>
<body>
  <header>
    <div class="container">
      <div class="logo">Crowdfunding</div>
      <nav class="headnav">
        <a href="index.php">Home</a>
        <a href="browse-fundraisers.php">Browse Fundraisers</a>
        <a href="How-it-works.html">How It Works</a>
        <a href="help.html">Help</a>
      </nav>
      <div class="actions">
        <button id="toggleBtn" class="btn secondary" onclick="toggleForm()">Sign In</button> <!-- Button triggers form toggle -->
      </div>
    </div>
  </header>

  <body>
            <div class="campaign-container">
                <!-- Campaign Photo -->
                <div class="campaign-photo">
                    <img src="uploads/<?php echo htmlspecialchars($fundraiser['photo']); ?>" alt="Campaign Photo">
                </div>

                <!-- Campaign Content -->
                <div class="campaign-content">
                    <h1><?php echo htmlspecialchars($fundraiser['person_name']); ?>'s Campaign</h1>
                    <!-- <p><strong>Purpose:</strong> <?php echo htmlspecialchars($fundraiser['purpose']); ?></p> -->
                    <p><strong>Amount Needed:</strong> ₹<?php echo number_format($fundraiser['amount'], 2); ?></p>
                    <p><strong>Phone:</strong> <?php echo htmlspecialchars($fundraiser['phone']); ?></p>
                    <p><strong>Description:</strong> <?php echo htmlspecialchars($fundraiser['description']); ?></p>

                    <!-- Progress bar -->
                    <div class="progress-bar-container">
                        <div class="progress-bar-fill" style="width: <?php echo $progress_percentage; ?>%;">
                            <?php echo round($progress_percentage); ?>%
                        </div>
                    </div>

                    <!-- Donation form -->
                    <div class="donate-section">
                        <form action="donate.php" method="POST" onsubmit="return validateDonation();">
                            <input type="hidden" name="campaign_link" value="<?php echo htmlspecialchars($fundraiser['campaign_link']); ?>">
                            <input type="number" id="donation_amount" name="amount" placeholder="Enter amount to donate" max="<?php echo $remaining_amount; ?>" required>
                            <button type="submit">Donate</button>
                        </form>
                        <p id="error_message"></p>
                        <p>Remaining amount needed: ₹<?php echo number_format($remaining_amount, 2); ?></p>
                    </div>

                    <!-- Share options -->
                    <div class="share-section">
                        <p>Share this campaign:</p>
                        <button onclick="copyToClipboard()">Copy Campaign Link</button>
                        <a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo urlencode($campaign_url); ?>" target="_blank">Share on Facebook</a>
                        <a href="https://twitter.com/intent/tweet?url=<?php echo urlencode($campaign_url); ?>&text=Support this campaign!" target="_blank">Share on Twitter</a>
                    </div>
                </div>
            </div>

            <script>
                // Client-side validation to ensure the donation amount does not exceed the remaining amount
                function validateDonation() {
                    var donationAmount = parseFloat(document.getElementById('donation_amount').value);
                    var maxDonationAmount = <?php echo $remaining_amount; ?>;

                    if (donationAmount > maxDonationAmount) {
                        document.getElementById('error_message').textContent = "You cannot donate more than the remaining amount: ₹" + maxDonationAmount.toFixed(2);
                        return false; // Prevent form submission
                    }
                    return true;
                }

                // Copy campaign URL to clipboard
                function copyToClipboard() {
                    var campaignLink = "<?php echo $campaign_url; ?>";
                    var tempInput = document.createElement("input");
                    tempInput.style = "position: absolute; left: -1000px; top: -1000px";
                    tempInput.value = campaignLink;
                    document.body.appendChild(tempInput);
                    tempInput.select();
                    document.execCommand("copy");
                    document.body.removeChild(tempInput);
                    alert("Campaign link copied to clipboard!");
                }
            </script>
        </body>

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
              <br>
            </nav>
          </div>
      <div class="footer-left">
        <p>&copy; 2024 Crowdfunding. All Rights Reserved.</p>
      </div>
      
    </div>
  </footer>
  <script>
    // Function to toggle between the register and login forms
    function toggleForm() {
      const registerForm = document.getElementById('register-form');
      const loginForm = document.getElementById('login-form');
      const overlay = document.getElementById('overlay');
      const popup = document.getElementById('popup');
      const toggleBtn = document.getElementById('toggleBtn');

      // Show popup and overlay
      overlay.style.display = 'block';
      popup.style.display = 'flex';

      // Toggle form visibility
      if (registerForm.style.display === 'none') {
        registerForm.style.display = 'block';
        loginForm.style.display = 'none';
        toggleBtn.textContent = 'Sign In';
      } else {
        registerForm.style.display = 'none';
        loginForm.style.display = 'block';
        toggleBtn.textContent = 'Sign Up';
      }
    }

    // Close the popup form
    function closeForm() {
      const overlay = document.getElementById('overlay');
      const popup = document.getElementById('popup');
      overlay.style.display = 'none';
      popup.style.display = 'none';
    }
  </script>
</body>
</html>
<?php
    } else {
        echo "Campaign not found.";
    }
} else {
    echo "No campaign link provided.";
}
?>
