<?php
include 'db.php'; // Include database connection

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Retrieve form data
    $name = isset($_POST['name']) ? trim($_POST['name']) : '';
    $country_code = isset($_POST['country_code']) ? trim($_POST['country_code']) : '';
    $phone = isset($_POST['phone']) ? trim($_POST['phone']) : '';
    $purpose = isset($_POST['purpose']) ? trim($_POST['purpose']) : '';
    $request_type = isset($_POST['request_type']) ? trim($_POST['request_type']) : '';

    // Combine country code with phone number
    $full_phone = $country_code . ' ' . $phone;

    // Input validation
    if (empty($name)) {
        echo "Name is required.";
        exit();
    }
    if (empty($country_code)) {
        echo "Country code is required.";
        exit();
    }
    if (empty($phone)) {
        echo "Phone number is required.";
        exit();
    }
    if (empty($purpose)) {
        echo "Purpose is required.";
        exit();
    }
    if (empty($request_type)) {
        echo "Request type is required.";
        exit();
    }

    // Insert the form data into the database
    $stmt = $conn->prepare("INSERT INTO assistance_requests (name, phone, purpose, request_type) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $full_phone, $purpose, $request_type);

    if ($stmt->execute()) {
        echo "Your request has been successfully submitted!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
} else {
    echo "Invalid request.";
}
?>
