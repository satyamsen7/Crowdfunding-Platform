<?php
session_start(); // Start the session to use session variables
include 'db.php'; // Include the database connection

// If user is already logged in, redirect to dashboard
if (isset($_SESSION['username'])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve user input (username or email) and password
    $loginInput = $_POST['loginInput'];  // Either username or email
    $password = $_POST['password'];      // Password

    // Prepare the SQL query to check if the input is a username or email
    $sql = "SELECT * FROM users WHERE username=? OR email=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $loginInput, $loginInput);  // Bind input for both username and email
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, fetch the user data
        $row = $result->fetch_assoc();
        
        // Verify the password
        if (password_verify($password, $row['password'])) {
            // Set session variables after successful login
            $_SESSION['username'] = $row['username']; // Store username in session
            $_SESSION['user_id'] = $row['id']; // Store user ID in session (optional)
            
            // Redirect to the dashboard
            header("Location: dashboard.php");
            exit();
        } else {
            echo "Invalid password!";
        }
    } else {
        echo "No user found!";
    }

    $stmt->close(); // Close the statement
}

$conn->close(); // Close the database connection
?>
