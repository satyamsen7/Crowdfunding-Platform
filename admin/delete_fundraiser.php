<?php
session_start();
include '../db.php'; // Include database connection

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header("Location: admin_login.php");
    exit();
}

// Check if the request is valid
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['campaign_id'])) {
    $campaign_id = intval($_POST['campaign_id']);

    // Fetch the image path for the fundraiser
    $stmt = $conn->prepare("SELECT photo FROM fundraisers WHERE campaign_id = ?");
    $stmt->bind_param("i", $campaign_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $image_path = "../uploads/" . $row['photo']; // Original file path

        // Check if the file exists and delete it
        if (file_exists($image_path)) {
            if (unlink($image_path)) {
                error_log("Image file deleted successfully: " . $image_path);
                $_SESSION['message'] = "Fundraiser and associated image deleted successfully.";
            } else {
                error_log("Failed to delete image file: " . $image_path);
                $_SESSION['error'] = "Fundraiser deleted, but failed to delete image.";
            }
        } else {
            error_log("Image file does not exist: " . $image_path);
            $_SESSION['error'] = "Fundraiser deleted, but image file not found.";
        }

        // Delete the fundraiser record
        $stmt_delete = $conn->prepare("DELETE FROM fundraisers WHERE campaign_id = ?");
        $stmt_delete->bind_param("i", $campaign_id);

        if ($stmt_delete->execute()) {
            error_log("Fundraiser record deleted successfully.");
        } else {
            $_SESSION['error'] = "Failed to delete fundraiser.";
            error_log("Failed to delete fundraiser record.");
        }

        $stmt_delete->close();
    } else {
        $_SESSION['error'] = "Fundraiser not found.";
        error_log("No fundraiser found with campaign_id: " . $campaign_id);
    }

    $stmt->close();
    $conn->close();

    // Redirect back to the admin fundraiser management page
    header("Location: manage_fundraisers.php");
    exit();
} else {
    header("Location: manage_fundraisers.php");
    exit();
}
?>
