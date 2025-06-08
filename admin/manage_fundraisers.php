<?php

include '../db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Fetch all fundraisers
$result = $conn->query("SELECT * FROM fundraisers ORDER BY campaign_id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Fundraisers</title>
    <link rel="stylesheet" href="admin_dashboard.css"> <!-- Link to your CSS file -->
</head>
<body>
    <header>
        <h1>Manage Fundraisers</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="manage_fundraisers.php">Fundraisers</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <?php
        // Display success or error messages
        if (isset($_SESSION['message'])) {
            echo "<div class='success-message'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='error-message'>{$_SESSION['error']}</div>";
            unset($_SESSION['error']);
        }
        ?>
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Person Name</th>
                    <th>Purpose</th>
                    <th>Amount</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['campaign_id']; ?></td>
                        <td><?php echo htmlspecialchars($row['username']); ?></td>
                        <td><?php echo htmlspecialchars($row['person_name']); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                        <td>
                            <!-- Delete Button -->
                            <form action="delete_fundraiser.php" method="POST" onsubmit="return confirm('Are you sure you want to delete this fundraiser?');">
                                <input type="hidden" name="campaign_id" value="<?php echo $row['campaign_id']; ?>">
                                <button type="submit" class="delete-btn">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Your Website. All Rights Reserved.</p>
    </footer>
</body>
</html>
