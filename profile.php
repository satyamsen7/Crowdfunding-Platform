<?php
// Start session
  include 'db.php'; // Include database connection
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Fetch user details
$sql = "SELECT fullname, username, email, phone FROM users WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "No user found.";
    exit();
}

// Handle profile update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $phone = $_POST['phone'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Prepare update query
    if (!empty($password)) {
        // Update password if new password is provided
        $update_sql = "UPDATE users SET fullname = ?, phone = ?, email = ?, password = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("ssssi", $fullname, $phone, $email, password_hash($password, PASSWORD_DEFAULT), $user_id);
    } else {
        // Do not update password if it's blank
        $update_sql = "UPDATE users SET fullname = ?, phone = ?, email = ? WHERE id = ?";
        $stmt = $conn->prepare($update_sql);
        $stmt->bind_param("sssi", $fullname, $phone, $email, $user_id);
    }

    if ($stmt->execute()) {
        echo "Profile updated successfully.";
        header("Location: profile.php");
        exit();
    } else {
        echo "Error updating profile.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
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
            /* Light background for the entire page */
            color: #333;
        }

        header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: white;
            padding: 10px 20px;
            border-bottom: 1px solid #ddd;
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
            justify-content: space-between;
        }

        .logo {
            font-size: 40px;
            font-weight: bold;
            color: #1abc9c;
            text-decoration: underline;
        }

        nav a {
            margin: 0px 40px;
            text-decoration: none;
            color: #333;
            transition: color 0.3s ease;
            font-size: 20px;

        }

        nav a:hover {
            color: #1abc9c;
            /* Hover effect */
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
            color: #1abc9c;
            /* Highlight color on hover */
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
        }

        form {
            display: flex;
            flex-direction: column;
            justify-content: center;
            background-color: #ffffff;
            /* White background for contrast */
            padding: 50px;
            gap: 10px;
            margin: 50px ;
            margin-left: 25%;
            width: 100%;
            max-width: 700px;
            /* Fixed width for the form */
            border-radius: 8px;
            /* Rounded corners */
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
            /* Subtle shadow */
            font-size: 20px;
            line-height: 0.3;
        }

        /* Label styling */
        form label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
            color: #333333;
            /* Darker color for better readability */
        }

        /* Input field styling */
        form input[type="text"],
        form input[type="email"],
        form input[type="password"] {
            width: 100%;
            /* Full width */
            padding: 15px;
            margin-bottom: 20px;
            /* Space between fields */
            border: 1px solid #ddd;
            /* Light border */
            border-radius: 4px;
            /* Rounded edges */
            font-size: 14px;
            box-sizing: border-box;
            /* Ensures consistent sizing */
            transition: border-color 0.3s ease;
        }

        /* Input hover and focus styling */
        form input:focus {
            outline: none;
            /* Remove default outline */
            border-color: #6a11cb;
            /* Highlight border */
            box-shadow: 0 0 5px rgba(106, 17, 203, 0.3);
            /* Add subtle glow */
        }

        /* Disabled input field styling */
        form input[disabled] {
            background-color: #f2f2f2;
            color: #999999;
            cursor: not-allowed;
        }

        /* Submit button styling */
        form button {
            width: 40%;
            /* Full width */
            padding: 25px;
            background-color: blue;
            /* Gradient button */
            color: white;
            font-weight: bold;
            border: none;
            /* Remove border */
            border-radius: 15px;
            /* Rounded button */
            cursor: pointer;
            transition: all 0.3s ease;
            /* Smooth hover effect */
        }

        /* Button hover effect */
        form button:hover {
            background-color: rgb(5, 136, 5);
            /* Darker gradient */
            transform: translateY(-3px);
            /* Slight lift */
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
            /* Shadow on hover */
        }

        /* Responsive styling */
        @media (max-width: 500px) {
            form {
                padding: 20px;
            }

            form label {
                font-size: 14px;
            }

            form input,
            form button {
                font-size: 14px;
            }
        }

    </style>
</head>

<body>
    <header>
        <div class="container  ">
            <div class="logo">
                <i class="ri-funds-line text-gray-500 text-5xl"></i>
                Crowdfunding
            </div>
            <nav class="headnav">
                <a href="dashboard.php">Dashboard</a>
                <a href="browse-fundraisers.php">Browse Fundraisers</a>
                <a href="How-it-works.html">How It Works</a>
                <a href="help.html">Help</a>
            </nav>
            <div>
                <a class="btn" href="logout.php" >Logout</a>
            </div>
    </header>

    <body>
    
        <form action="profile.php" method="POST">
            
            <h1 style="font-size:40px">Profile</h1>
            <br>
            <label for="fullname">Full Name:</label>
            <input type="text" name="fullname" value="<?php echo htmlspecialchars($user['fullname']); ?>"
                required><br><br>

            <label for="username">Username:</label>
            <input type="text" name="username" value="<?php echo htmlspecialchars($user['username']); ?>"
                disabled><br><br>

            <label for="email">Email:</label>
            <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required><br><br>

            <label for="phone">Phone:</label>
            <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required><br><br>

            <label for="password">New Password (Leave blank if you don't want to change):</label>
            <input type="password" name="password" placeholder="Enter new password"><br><br>

            <button type="submit">Update Profile</button>
        </form>

       
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
<?php
$conn->close();
?>