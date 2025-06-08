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
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />
  
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
  padding: 0;
}

header {
  display: flex;
  align-items: center;
  justify-content: space-between;
  background: white;
  padding: 10px 20px;
  border-bottom: 1px solid #ddd;
}

header .container {
  display: flex;
  align-items: center;
  justify-content: space-between;
  width: 100%;
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

header .logout-btn {
  margin-left: 30px;
}

.logout-btn a {
  display: inline-block;
  background-color: #1abc9c;
  color: white;
  padding: 10px 20px;
  font-size: 18px;
  font-weight: 600;
  text-transform: uppercase;
  border-radius: 50px;
  text-decoration: none;
  box-shadow: 0 8px 15px rgba(0, 0, 0, 0.2);
  transition: all 0.3s ease;
}

.logout-btn a:hover {
  background-color: #16a085;
  transform: translateY(-3px);
  box-shadow: 0 12px 20px rgba(0, 0, 0, 0.3);
}


    .form-container {
      margin: 40px auto;
      max-width: 800px;
      background-color: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .form-container h1 {
      text-align: center;
      color: #333;
      margin-bottom: 20px;
    }

    .form-container label {
      font-size: 16px;
      margin-bottom: 5px;
      color: #555;
    }

    .form-container input,
    .form-container select,
    .form-container textarea,
    .form-container button {
      width: 100%;
      padding: 10px;
      margin: 10px 0;
      border: 1px solid #ddd;
      border-radius: 5px;
      font-size: 16px;
      color: #333;
    }

    .form-container textarea {
      resize: vertical;
    }

    .form-container button {
      background-color: #1abc9c;
      color: white;
      border: none;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    .form-container button:hover {
      background-color: #16a085;
    }

    footer {
      background-color: white;
      color: #333;
      padding: 30px 20px;
      border-top: 1px solid #ddd;
      text-align: center;
      box-shadow: 0 -2px 5px rgba(0, 0, 0, 0.1);
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
            <a href="dashboard.php">Dashboard</a>
            <a href="browse-fundraisers.php">Browse Fundraisers</a>
            <a href="How-it-works.html">How It Works</a>
            <a href="help.html">Help</a>
        </nav>
        <!-- Logout Button -->
        <div class="logout-btn">
            <a href="logout.php">Logout</a>
        </div>
    </div>
</header>


  <div class="form-container">
    <h1>Create Fundraiser</h1>
    <form action="create_fundraiser.php" method="POST" enctype="multipart/form-data">
      <label for="person_name">Title</label>
      <input type="text" id="person_name" name="person_name" required>

      <label for="purpose">Purpose</label>
      <textarea id="purpose" name="purpose" required></textarea>

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
      </select>

      <label for="amount">Amount Needed</label>
      <input type="number" id="amount" name="amount" required>

      <label for="phone">Phone</label>
      <input type="text" id="phone" name="phone" required>

      <label for="description">Description</label>
      <textarea id="description" name="description"></textarea>

      <label for="photo">Photo</label>
      <input type="file" id="photo" name="photo" required>

      <button type="submit" name="create_fundraiser">Create Fundraiser</button>
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
  </div>

  <footer>
    <div class="container">
      <div class="footer-left">
        <p>&copy; 2024 Crowdfunding. All Rights Reserved.</p>
      </div>
      <div class="footer-right">
        <nav>
          <a href="privacy-policy.html">Privacy Policy</a>
          <a href="terms-and-conditions.html">Terms & Conditions</a>
          <a href="contact-us.html">Contact Us</a>
        </nav>
      </div>
    </div>
  </footer>

</body>
</html>

