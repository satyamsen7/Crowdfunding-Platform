<?php

// Start the session at the beginning


// Include your database connection
require_once "db.php";

// Check if the user is logged in (session variable should be set)
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Debugging: Check session value
// echo "Session User ID: " . $user_id;

// Fetch user details
$query = $conn->prepare("SELECT fullname, balance FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows === 0) {
    die("Error: User not found in the database. Check your session or database records.");
}

$user = $result->fetch_assoc();
$fullname = $user['fullname'];
$current_balance = $user['balance'];

// Process withdrawal request
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $upi_id = trim($_POST['upi_id']);
    $withdraw_amount = floatval($_POST['amount']);

    // Validate input
    if (empty($upi_id)) {
        echo "<p style='color: red;'>Error: UPI ID is required.</p>";
    } elseif ($withdraw_amount <= 0) {
        echo "<p style='color: red;'>Error: Invalid withdrawal amount.</p>";
    } elseif ($withdraw_amount > $current_balance) {
        echo "<p style='color: red;'>Error: Insufficient balance.</p>";
    } else {
        // Deduct amount from balance and record the transaction
        $new_balance = $current_balance - $withdraw_amount;
        $update_query = $conn->prepare("UPDATE users SET balance = ? WHERE id = ?");
        $update_query->bind_param("di", $new_balance, $user_id);

        $insert_query = $conn->prepare("INSERT INTO withdrawals (user_id, upi_id, amount, requested_at, status) VALUES (?, ?, ?, NOW(), 'Processing')");
        $insert_query->bind_param("isd", $user_id, $upi_id, $withdraw_amount);

        if ($update_query->execute() && $insert_query->execute()) {
            $current_balance = $new_balance; // Update current balance for display
            echo "<p style='color: green;'>Withdrawal request of ₹{$withdraw_amount} successfully submitted!</p>";
        } else {
            echo "<p style='color: red;'>Error: Unable to process the withdrawal.</p>";
        }
    }
}

// Fetch withdrawal history
$history_query = $conn->prepare("SELECT upi_id, amount, requested_at, status FROM withdrawals WHERE user_id = ? ORDER BY requested_at DESC");
$history_query->bind_param("i", $user_id);
$history_query->execute();
$history_result = $history_query->get_result();
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
    <script src="https://cdn.tailwindcss.com"></script>
    <title>Header and Footer Page</title>
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
        background-color: #f4f4f4;
        color: #333;
    }

    header {
        background: white;
        padding: 10px 30px;
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
        display: flex;
        align-items: center;
        gap: 10px;
    }

    header nav a {
        margin: 0 20px;
        text-decoration: none;
        color: #333;
        transition: color 0.3s ease;
        font-size: 18px;
    }

    header nav a:hover {
        color: #1abc9c;
    }

    .btn-secondary {
        background-color: #1abc9c;
        color: white;
        padding: 10px 20px;
        font-size: 14px;
        font-weight: bold;
        border: none;
        border-radius: 50px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .btn-secondary:hover {
        background-color: #16a085;
        transform: translateY(-3px);
        box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
    }

    .page {
        padding: 20px;
        font-size: 22px;
    }

    .total-balance,
    .funds {
        padding-left: 12%;
    }

    .funds {
        font-size: 1.8rem;
    }

    form {
        padding: 20px;
        padding-left: 12%;
    }

    form label {
        display: block;
        margin-bottom: 5px;
        font-size: 14px;
        font-weight: bold;
    }

    form input {
        width: 40%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 5px;
        font-size: 14px;
    }

    form button {
        font-size: 16px;
        padding: 10px 20px;
        background-color: blue;
        color: white;
        border: none;
        border-radius: 15px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    form button:hover {
        background-color: rgb(5, 136, 5);
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .history {
        padding-left: 12%;
        margin-top: 20px;
        font-size: 18px;
        color: blue;
        text-decoration: underline;
        cursor: pointer;
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
        flex-wrap: wrap;
    }

    footer .footer-left {
        font-size: 14px;
        color: #888;
    }

    footer nav a {
        margin: 0 10px;
        text-decoration: none;
        color: #333;
        font-size: 14px;
        transition: color 0.3s ease;
    }

    footer nav a:hover {
        color: #1abc9c;
    }

    @media (max-width: 768px) {
        header .container {
            flex-direction: column;
            text-align: center;
        }

        footer .container {
            flex-direction: column;
            text-align: center;
        }

        .history {
            text-align: center;
        }

        form {
            padding-left: 5%;
        }
    }
    .page {
        padding: 20px;
        max-width: 1200px;
        margin: 0 auto;
        font-size: 18px;
        background-color: #fff;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        border-radius: 8px;
    }

    .welcome-title {
        font-size: 2rem;
        font-weight: bold;
        color: #1abc9c;
        margin-bottom: 20px;
    }

    .total-balance {
        font-size: 1.5rem;
        margin-bottom: 30px;
        font-weight: bold;
        color: #333;
    }

    .label {
        color: #888;
    }

    .amount {
        color: #1abc9c;
    }

    .withdraw-section {
        margin-bottom: 40px;
    }

    .funds {
        font-size: 2rem;
        color: #333;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .withdraw-form {
        display: flex;
        flex-direction: column;
        gap: 15px;
    }

    .form-group {
        display: flex;
        flex-direction: column;
    }

    .form-group label {
        font-size: 16px;
        margin-bottom: 5px;
        font-weight: bold;
        color: #555;
    }

    .form-group input {
        padding: 10px;
        font-size: 16px;
        border: 1px solid #ddd;
        border-radius: 5px;
        width: 100%;
        box-sizing: border-box;
        transition: border-color 0.3s ease;
    }

    .form-group input:focus {
        border-color: #1abc9c;
    }
    
    .btn {
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
            background-color: #16a085;
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }.btn {
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
            background-color: #16a085;
            transform: translateY(-3px);
            box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
        }

    .submit-btn {
        padding: 10px 20px;
        background-color: #1abc9c;
        color: white;
        font-size: 16px;
        font-weight: bold;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .submit-btn:hover {
        background-color: #16a085;
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .history-section {
        margin-top: 50px;
    }

    .history {
        font-size: 2rem;
        color: #333;
        margin-bottom: 15px;
        font-weight: bold;
    }

    .history-table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 15px;
    }

    .history-table th,
    .history-table td {
        padding: 12px;
        text-align: center;
        border: 1px solid #ddd;
    }

    .history-table th {
        background-color: #f8f8f8;
        color: #333;
    }

    .history-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }

    .history-table tr:hover {
        background-color: #f1f1f1;
    }

    /* Mobile-Responsive Adjustments */
    @media (max-width: 768px) {
        .page {
            padding: 10px;
            font-size: 16px;
        }

        .welcome-title {
            font-size: 1.5rem;
        }

        .withdraw-form,
        .history-table {
            width: 100%;
        }

        .history-table th, .history-table td {
            font-size: 14px;
        }
    }
</style>

</head>

<body>
    <header>
        <div class="container">
            <div class="logo">
                <i class="ri-funds-line text-gray-500 text-5xl"></i>
                Crowdfunding
            </div>
            <nav class="headnav">
                <a href="index.php">Home</a>
                <a href="browse-fundraisers.php">Browse Fundraisers</a>
                <a href="How-it-works.html">How It Works</a>
                <a href="help.html">Help</a>
            </nav>
            <div>
            <a href="dashboard.php" class="btn">Dashboard</a>
                <!-- Button triggers form toggle -->
            </div>
        </div>
    </header>

    <body>
    <div class="page">
        <h1 class="welcome-title">Welcome, 
            <?php echo htmlspecialchars($fullname); ?>
        </h1>
        
        <div class="total-balance">
            <span class="label">Total Balance:</span>
            <span class="amount">₹ 
                <?php echo number_format($user['balance'], 2); ?>
            </span>
        </div>

        <section class="withdraw-section">
            <h2 class="funds">Withdraw Funds</h2>
            <form method="POST" class="withdraw-form">
                <div class="form-group">
                    <label for="upi_id">UPI ID:</label>
                    <input type="text" name="upi_id" id="upi_id" required placeholder="Enter your UPI ID">
                </div>
                <div class="form-group">
                    <label for="amount">Amount (₹):</label>
                    <input type="number" name="amount" id="amount" step="0.01" required placeholder="Enter amount">
                </div>
                <button type="submit" class="submit-btn">Submit Withdrawal</button>
            </form>
        </section>

        <section class="history-section">
            <h2 class="history">Withdrawal History</h2>
            <table class="history-table">
                <tr>
                    <th>UPI ID</th>
                    <th>Amount (₹)</th>
                    <th>Requested At</th>
                    <th>Status</th>
                </tr>
                <?php while ($row = $history_result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['upi_id']); ?></td>
                    <td>₹ <?php echo number_format($row['amount'], 2); ?></td>
                    <td><?php echo $row['requested_at']; ?></td>
                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                </tr>
                <?php endwhile; ?>
            </table>
        </section>
    </div>
</body>


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

</body>

</html>
