<?php
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.php"); // Redirect to login page if not logged in
    exit();
}

// Check if a success message exists in the session
if (isset($_SESSION['message'])) {
    echo "<p style='color: green;'>" . $_SESSION['message'] . "</p>";
    unset($_SESSION['message']); // Remove the message after displaying it
}

// Fetch logged-in user's details
$username = $_SESSION['username'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Fetch user's active campaigns and total donations
$stmt_fundraisers = $conn->prepare("
    SELECT f.campaign_id, f.person_name, f.amount AS goal, 
           IFNULL(SUM(d.amount), 0) AS raised, f.campaign_link
    FROM fundraisers f
    LEFT JOIN donations d ON f.campaign_link = d.campaign_link
    WHERE f.username = ?
    GROUP BY f.campaign_id
");
$stmt_fundraisers->bind_param("s", $username);
$stmt_fundraisers->execute();
$result_fundraisers = $stmt_fundraisers->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <title>User Dashboard</title>
    <link
    href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css"
    rel="stylesheet"
/>
    <style>
       /* General Reset */
       * {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
}

/* HTML and Body */
html, body {
  height: 100%;
}

body {
  display: flex;
  flex-direction: column;
  font-family: Arial, sans-serif;
  background-color: #f4f4f4;
  color: #333;
  line-height: 1.6;
}

header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
        }

header .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
}

header .logo {
  font-size: 40px;
  font-weight: bold;
  text-decoration: underline;
  color: #1abc9c;
}

header nav a {
  margin: 0px 30px;
  text-decoration: none;
  color: #333;
  transition: color 0.3s ease;
  font-size: 20px;
}

header nav a:hover {
  color: #1abc9c;
}

header .actions .btn {
  background-color: #1abc9c;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
  font-size: 14px;
}

header .actions .btn:hover {
  background-color: #1a7e6a;
}

main .container {
  flex: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  background: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
}

/* Main Content */
main {
  flex: 1;
  max-width: 1200px;
  margin: 0 auto;
  padding: 20px;
  background: white;
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
  border-radius: 5px;
}



/* Footer Styles */
/* Footer Styles */
footer {
  background: white;
  color: #333;
  padding: 20px;
  text-align: center;
  border-top: 1px solid #ddd;
  width: 100%;
  margin-top: auto;
}

footer .container {
  display: flex;
  justify-content: space-between;
  align-items: center;
  max-width: 1200px;
  margin: 0 auto;
}

footer .footer-right a {
  text-decoration: none;
  color: #333;
  margin: 0 10px;
  font-size: 14px;
  transition: color 0.3s ease;
}

footer .footer-right a:hover {
  color: #1abc9c;
}

footer .footer-left {
  font-size: 14px;
  color: #666;
}

/* Table Styles */
table {
  width: 100%;
  border-collapse: collapse;
  margin-top: 20px;
}

th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}

th {
  background-color: #1abc9c;
  color: white;
}

.total-balance {
  font-size: 1.5em;
  font-weight: bold;
  color: #333;
  margin-bottom: 20px;
}

.btn {
  background-color: #1abc9c;
      color: white; 
      padding: 15px 30px; 
      margin: 20px 150px;
      font-size: 16px; 
      font-weight: 400;
      text-transform: uppercase; 
      border: none; 
      border-radius: 50px; 
      box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2); 
      cursor: pointer; 
      transition: all 0.3s ease; 
      display: flex;
      align-items: center;
      justify-content: center;

    }

    .btn2 {
            background-color: #1abc9c;
            color: white;
            padding: 15px 30px;
            font-size: 18px;
            font-weight: 600;
            text-transform: uppercase;
            border: none;
            border-radius: 50px;
            box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
            cursor: pointer;
            transition: all 0.3s ease;

        }


.btn:hover {
  /* background-color: #16a085; */
  transform: translateY(-3px); /* Lift the button */
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3); /* More pronounced shadow */
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
}

    </style>
</head>
<body>
    <header>
        <div class="container">
            <div class="logo">
                <i class="ri-funds-line text-gray-500 text-5xl"></i>
                Crowdfunding</div>
            <nav class="headnav">
                <a href="index.php">Home</a>
                <a href="browse-fundraisers.php">Browse Fundraisers</a>
                <a href="How-it-works.html">How It Works</a>
                <a href="help.html">Help</a>
                
            </nav>
            <div> <a href="profile.php" class="btn2">Profile</a> </div>
            
        </div>
        
    </header>

    <main>
        <div class="container">
            <h1>Welcome, <?php echo htmlspecialchars($user['fullname']); ?></h1>
            <div class="total-balance">
                Total Balance: ₹<?php echo number_format($user['balance'], 2); ?>
            </div>
            <h2>Your Actions</h2>
            <a href="create_fundraiser.php" class="btn">Create Fundraiser</a>
            <a href="withdraw_funds.php" class="btn">Withdraw Funds</a>
            <a href="logout.php" class="btn">Logout</a>

            <h2>Your Running Campaigns</h2>
            <?php if ($result_fundraisers->num_rows > 0): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Campaign</th>
                            <th>Goal Amount</th>
                            <th>Raised Amount</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fundraiser = $result_fundraisers->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fundraiser['person_name']); ?></td>
                                <td>₹<?php echo number_format($fundraiser['goal'], 2); ?></td>
                                <td>₹<?php echo number_format($fundraiser['raised'], 2); ?></td>
                                <td>
                                    <a href="view_campaign.php?campaign_link=<?php echo urlencode($fundraiser['campaign_link']); ?>" class="btn">View</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>You don't have any running campaigns.</p>
            <?php endif; ?>
        </div>
    </main>
    
    

    <footer class=footer>
        <div class="container">
            <div class="footer-right">
                <nav>
                    <a href="privacy-policy.html">Privacy Policy</a>
                    <a href="terms-and-conditions.html">Terms & Conditions</a>
                    <a href="contact-us.html">Contact Us</a>
                </nav>
            </div>
            <div class="footer-left">
                <p>&copy; 2024 Crowdfunding. All Rights Reserved.</p>
            </div>
        </div>
    </footer>

    
</body>
</html>