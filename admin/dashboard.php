<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="admin_dashboard.css">

    
</head>
<body>
    <header>
        <h1>Welcome, <?php echo htmlspecialchars($_SESSION['admin_name']); ?></h1>
    </header>
    <main>
        <section>
            <h2>Admin Details</h2>
            <p><strong>Full Name:</strong> <?php echo htmlspecialchars($_SESSION['admin_name']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($_SESSION['admin_username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($_SESSION['admin_email']); ?></p>
        </section>
        <nav>
            <a href="dashboard.php">Dashboard</a>
            <a href="contact_submissions.php">View Contact Submissions</a>
            <a href="view_assistance_requests.php">Assistance Requests</a>
            <a href="manage_fundraisers.php">Manage fundraisers</a>
            <a href="manage_withdrawal_request.php">Manage withdrawal requests</a>
            <a href="logout.php">Logout</a>
        </nav>
       


    </main>
    <footer>
        <p>&copy; 2024 Your Website. All Rights Reserved.</p>
    </footer>
</body>
</html>
