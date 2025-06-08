<?php
include '../db.php'; // Include database connection

if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}



// Fetch all assistance requests
$result = $conn->query("SELECT * FROM assistance_requests ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assistance Requests</title>
    <link rel="stylesheet" href="assistance_requests.css">

    
</head>
<body>
    <header>
        <h1>Assistance Requests</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="contact_submissions.php">Contact Submissions</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <h2>Requests for a Call</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Purpose</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display only "call" requests
                $call_requests = $conn->query("SELECT * FROM assistance_requests WHERE request_type = 'call' ORDER BY submitted_at DESC");
                while ($row = $call_requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>

        <h2>Requests for WhatsApp</h2>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Purpose</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display only "whatsapp" requests
                $whatsapp_requests = $conn->query("SELECT * FROM assistance_requests WHERE request_type = 'whatsapp' ORDER BY submitted_at DESC");
                while ($row = $whatsapp_requests->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['phone']); ?></td>
                        <td><?php echo htmlspecialchars($row['purpose']); ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Ketto. All Rights Reserved.</p>
    </footer>
</body>
</html>
