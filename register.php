<?php
include 'db.php'; // Include the database connection file

session_start(); // Start the session to store user data

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect the input data from the form
    $fullname = $_POST['fullname'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Check if the passwords match
    if ($password !== $confirm_password) {
        echo "Passwords do not match!";
        exit();
    }

    // Hash the password before storing
    $hashed_password = password_hash($password, PASSWORD_BCRYPT);

    // Prepare the SQL query to insert data into the 'users' table
    $sql = "INSERT INTO users (fullname, username, email, phone, password) 
            VALUES ('$fullname', '$username', '$email', '$phone', '$hashed_password')";

    // Execute the query and check if successful
    if ($conn->query($sql) === TRUE) {
        // Fetch the user data from the database (to log them in)
        $user_id = $conn->insert_id; // Get the ID of the newly inserted user
        $sql = "SELECT * FROM users WHERE id = $user_id";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();

            // Store the user's information in the session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['email'] = $user['email'];

            // Redirect the user to the dashboard or any other protected page
            header("Location: dashboard.php");
            exit();
        }
    } else {
        // If there was an error, display it
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close(); // Close the database connection
?>
