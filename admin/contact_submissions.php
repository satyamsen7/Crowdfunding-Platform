<?php
include '../db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    // Redirect to login page if not logged in
    header("Location: admin_login.php");
    exit();
}



// Fetch all contact form submissions
$result = $conn->query("SELECT * FROM contact_form ORDER BY submitted_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact Form Submissions</title>
    <link rel="stylesheet" href="contact_submissions.css">

</head>
<body>
    <header>
        <h1>Contact Form Submissions</h1>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="view_assistance_requests.php">Assistance Requests</a>
            <a href="logout.php">Logout</a>
        </nav>
    </header>
    <main>
        <table border="1" cellpadding="10">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Message</th>
                    <th>Submitted At</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo htmlspecialchars($row['name']); ?></td>
                        <td><?php echo htmlspecialchars($row['email']); ?></td>
                        <td><?php echo nl2br(htmlspecialchars($row['message'])); ?></td>
                        <td><?php echo $row['submitted_at']; ?></td>
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
