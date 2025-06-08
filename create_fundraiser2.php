<?php
include 'db.php'; // Include database connection

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: index.html"); // Redirect to login page if not logged in
    exit();
}

// Fetch logged-in user's details
$username = $_SESSION['username'];

// Use prepared statements to prevent SQL injection
$stmt = $conn->prepare("SELECT id, fullname FROM users WHERE username=?");
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();
$user_id = $user['id']; // Assuming the user's ID is stored as 'id' in the users table

// Function to generate a unique campaign link
function generateCampaignLink() {
    return "campaign_" . uniqid();
}

// Function to generate a unique filename
function generateFilename($username) {
    $random_number = random_int(100000, 999999);
    return $username . '_' . $random_number . '.jpg';
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Fundraiser</title>
    <!-- <link rel="stylesheet" href="css/create_fundraiser.css"> -->
    <style>
/* General Reset */
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

/* Body Styling */
body {
    font-family: Arial, sans-serif;
    background-color: #f8f8f8;
    color: #333;
    margin: 0;
    padding: 0;
}

/* Main Container Styling */
.container {
    max-width: 900px;
    margin: 50px auto;
    padding: 20px;
    background-color: white;
    border-radius: 8px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
}

/* Headings */
h1, h2 {
    color: #1abc9c;
    margin-bottom: 15px;
}

h1 {
    font-size: 28px;
}

h2 {
    font-size: 24px;
}

/* Profile Section Styling */
.profile-info p {
    margin: 10px 0;
    font-size: 18px;
}

.profile-info p strong {
    color: #1abc9c;
}

/* Form Styling */
form {
    margin-top: 20px;
}

form input,
form textarea,
form button {
    width: 100%;
    padding: 12px;
    margin: 10px 0;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 16px;
}

/* Textarea Styling */
form textarea {
    resize: vertical;
    min-height: 100px;
}

/* Submit Button Styling */
form button {
    background-color: #1abc9c;
    color: white;
    cursor: pointer;
    border: none;
    transition: background-color 0.3s ease;
}

form button:hover {
    background-color: #16a085;
}

/* Campaign List Styling */
ul {
    list-style-type: none;
    padding: 0;
}

ul li {
    padding: 15px;
    background-color: #fafafa;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
    margin-bottom: 20px;
}

ul li p {
    font-size: 16px;
    margin: 5px 0;
}

ul li p strong {
    color: #1abc9c;
}

/* Campaign Image Styling */
ul li img {
    max-width: 300px;
    border-radius: 8px;
    margin-top: 10px;
}

/* Link Styling */
a {
    color: #1abc9c;
    text-decoration: none;
}

a:hover {
    text-decoration: underline;
}

/* Empty State */
.no-campaigns {
    font-size: 18px;
    color: #888;
    margin-top: 20px;
}

    </style>
</head>
<body>
    <h1>Create Fundraiser</h1>
    <form action="create_fundraiser.php" method="POST" enctype="multipart/form-data">
        <label for="person_name">Title</label><br>
        <input type="text" id="person_name" name="person_name" required><br><br>

        <label for="purpose">Purpose:</label><br>
        <textarea id="purpose" name="purpose" required></textarea><br><br>

        <label for="category">Category</label>
        <select name="category" id="category" required>
            <option value="Education">Education</option>
            <option value="Medical">Medical</option>
            <option value="Women & Girls">Women & Girls</option>
            <option value="Animals">Animals</option>
            <option value="Creative">Creative</option>
            <option value="Food & Hunger">Food & Hunger</option>
            <option value="Environment">Environment</option>
            <option value="Children">Children</option>
            <option value="Memorial">Memorial</option>
            <option value="Community Development">Community Development</option>
            <option value="Others">Others</option>
        </select><br><br>

        <label for="amount">Amount Needed:</label><br>
        <input type="number" id="amount" name="amount" required><br><br>

        <label for="phone">Phone:</label><br>
        <input type="text" id="phone" name="phone" required><br><br>

        <label for="description">Description:</label><br>
        <textarea id="description" name="description"></textarea><br><br>

        <label for="photo">Photo:</label><br>
        <input type="file" id="photo" name="photo" required><br><br>

        <input type="submit" name="create_fundraiser" value="Create Fundraiser">
    </form>

    <?php
    if (isset($_POST['create_fundraiser'])) {
        $person_name = $_POST['person_name'];
        $purpose = $_POST['purpose'];
        $category = $_POST['category'];  // Fetch 'category' value
        $amount = $_POST['amount'];
        $phone = $_POST['phone'];
        $description = $_POST['description'];

        // Handle file upload
        $photo = $_FILES['photo']['name'];
        $unique_filename = generateFilename($username);
        $target_dir = "uploads/";
        $target_file = $target_dir . $unique_filename;
        $uploadOk = 1;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

        // Check if image file is an actual image or fake image
        $check = getimagesize($_FILES['photo']['tmp_name']);
        if ($check !== false) {
            echo "File is an image - " . $check["mime"] . ".<br>";
            $uploadOk = 1;
        } else {
            echo "File is not an image.<br>";
            $uploadOk = 0;
        }

        // Check file size
        if ($_FILES['photo']['size'] > 500000) {
            echo "Sorry, your file is too large.<br>";
            $uploadOk = 0;
        }

        // Allow certain file formats
        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
            echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.<br>";
            $uploadOk = 0;
        }

        // Check if $uploadOk is set to 0 by an error
        if ($uploadOk == 0) {
            echo "Sorry, your file was not uploaded.<br>";
        } else {
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                echo "The file " . htmlspecialchars($unique_filename) . " has been uploaded.<br>";
            } else {
                echo "Sorry, there was an error uploading your file.<br>";
            }
        }

        // Generate a unique campaign link
        $campaign_link = generateCampaignLink();

        // Insert data into the database
        $stmt_insert = $conn->prepare("INSERT INTO fundraisers (user_id, username, person_name, category, purpose, amount, phone, description, photo, campaign_link) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt_insert->bind_param("isssdsssss", $user_id, $username, $person_name, $category, $purpose, $amount, $phone, $description, $unique_filename, $campaign_link);

        if ($stmt_insert->execute()) {
            // Set a session variable to show a success message on dashboard.php
            $_SESSION['message'] = "Fundraiser created successfully!";
            header("Location: dashboard.php"); // Redirect to dashboard.php
            exit();
        } else {
            echo "Error: " . $stmt_insert->error;
        }

        $stmt_insert->close();
        $conn->close();
    }
    ?>
</body>
</html>
