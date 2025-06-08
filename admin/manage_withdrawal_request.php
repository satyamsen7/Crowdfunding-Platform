<?php
// Include the database connection
require_once "../db.php";

// Handle search functionality
$search_username = '';
if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search_username = trim($_GET['search']);
}

// Query for all withdrawals based on status and optional search criteria
function fetch_withdrawals($conn, $status, $search_username = '') {
    $query = "SELECT w.id, w.user_id, u.username, w.upi_id, w.amount, w.requested_at 
              FROM withdrawals w
              JOIN users u ON w.user_id = u.id
              WHERE w.status = ?";

    if (!empty($search_username)) {
        $query .= " AND u.username LIKE ?";
        $stmt = $conn->prepare($query);
        $like_username = "%$search_username%";
        $stmt->bind_param("ss", $status, $like_username);
    } else {
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $status);
    }

    $stmt->execute();
    return $stmt->get_result();
}

// Check if a status update request was submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['withdrawal_id'], $_POST['status'])) {
    $withdrawal_id = intval($_POST['withdrawal_id']);
    $status = $_POST['status']; // Either 'Processing', 'Success', or 'Failed'

    // Validate the input
    if (in_array($status, ['Processing', 'Success', 'Failed'])) {
        // Update the withdrawal status
        $stmt = $conn->prepare("UPDATE withdrawals SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $withdrawal_id);

        if ($stmt->execute()) {
            echo "<p style='color: green;'>Withdrawal status updated successfully!</p>";
        } else {
            echo "<p style='color: red;'>Error updating withdrawal status.</p>";
        }
    } else {
        echo "<p style='color: red;'>Invalid status provided.</p>";
    }
}

// Fetch withdrawals
$processing_withdrawals = fetch_withdrawals($conn, 'Processing', $search_username);
$approved_withdrawals = fetch_withdrawals($conn, 'Success', $search_username);
$rejected_withdrawals = fetch_withdrawals($conn, 'Failed', $search_username);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Withdrawal Requests</title>
    <link rel="stylesheet" href="manage_withdrawal_request.css">
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 10px;
            text-align: center;
        }
        h2 {
            margin-top: 40px;
        }
        form {
            display: inline;
        }
        .search-bar {
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <h1>Manage Withdrawal Requests</h1>

    <!-- Search Bar -->
    <div class="search-bar">
        <form method="GET" style="display: inline;">
            <label for="search">Search by Username:</label>
            <input type="text" id="search" name="search" value="<?php echo htmlspecialchars($search_username); ?>">
            <button type="submit">Search</button>
        </form>
        <!-- Reset Search Button -->
        <form method="GET" style="display: inline;">
            <button type="submit" name="search" value="">Reset Search</button>
        </form>
    </div>

    <!-- Processing Withdrawals -->
    <h2>Processing Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>UPI ID</th>
            <th>Amount</th>
            <th>Requested At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $processing_withdrawals->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['upi_id']); ?></td>
                <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['requested_at']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="withdrawal_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="status" value="Success">Approve</button>
                        <button type="submit" name="status" value="Failed">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Approved Withdrawals -->
    <h2>Approved Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>UPI ID</th>
            <th>Amount</th>
            <th>Approved At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $approved_withdrawals->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['upi_id']); ?></td>
                <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['requested_at']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="withdrawal_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="status" value="Processing">Revert to Processing</button>
                        <button type="submit" name="status" value="Failed">Reject</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>

    <!-- Rejected Withdrawals -->
    <h2>Rejected Requests</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>Username</th>
            <th>UPI ID</th>
            <th>Amount</th>
            <th>Rejected At</th>
            <th>Action</th>
        </tr>
        <?php while ($row = $rejected_withdrawals->fetch_assoc()): ?>
            <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo htmlspecialchars($row['username']); ?></td>
                <td><?php echo htmlspecialchars($row['upi_id']); ?></td>
                <td>₹<?php echo number_format($row['amount'], 2); ?></td>
                <td><?php echo $row['requested_at']; ?></td>
                <td>
                    <form method="POST">
                        <input type="hidden" name="withdrawal_id" value="<?php echo $row['id']; ?>">
                        <button type="submit" name="status" value="Processing">Revert to Processing</button>
                        <button type="submit" name="status" value="Success">Approve</button>
                    </form>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>
</html>
