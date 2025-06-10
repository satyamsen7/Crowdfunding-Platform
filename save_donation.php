<?php
session_start();
include 'db.php'; // Include database connection

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get POST data
    $campaign_link = $_POST['campaign_link'];
    $donation_amount = $_POST['amount'];
    $payment_id = $_POST['payment_id']; // Assuming payment_id is passed from payment gateway

    // Fetch the campaign details
    $stmt_campaign = $conn->prepare("SELECT * FROM fundraisers WHERE campaign_link = ?");
    $stmt_campaign->bind_param("s", $campaign_link);
    $stmt_campaign->execute();
    $result_campaign = $stmt_campaign->get_result();

    if ($result_campaign->num_rows > 0) {
        $fundraiser = $result_campaign->fetch_assoc();

        // Fetch the fundraiser creator
        $creator_username = $fundraiser['username']; // Assuming 'username' stores the creator of the fundraiser
        $stmt_creator = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt_creator->bind_param("s", $creator_username);
        $stmt_creator->execute();
        $result_creator = $stmt_creator->get_result();

        if ($result_creator->num_rows > 0) {
            $creator = $result_creator->fetch_assoc();

            // Update the fundraiser creator's balance
            $new_balance = $creator['balance'] + $donation_amount;
            $stmt_update_creator = $conn->prepare("UPDATE users SET balance = ? WHERE username = ?");
            $stmt_update_creator->bind_param("ds", $new_balance, $creator_username);
            $stmt_update_creator->execute();

            // Insert the donation into the donations table
            $stmt_insert_donation = $conn->prepare("INSERT INTO donations (campaign_link, amount, payment_id) VALUES (?, ?, ?)");
            $stmt_insert_donation->bind_param("sds", $campaign_link, $donation_amount, $payment_id);
            $stmt_insert_donation->execute();

            // Update the raised amount for the fundraiser (not the original target amount)
            $new_raised = $fundraiser['raised'] + $donation_amount; // Assuming 'raised' tracks the donations collected
            $stmt_update_fundraiser = $conn->prepare("UPDATE fundraisers SET raised = ? WHERE campaign_link = ?");
            $stmt_update_fundraiser->bind_param("ds", $new_raised, $campaign_link);
            $stmt_update_fundraiser->execute();

            // Success message
            $_SESSION['message'] = "Donation successful! The fundraiser creator's balance has been updated.";
            header("Location: dashboard.php"); // Redirect to the dashboard
            exit();
        } else {
            $_SESSION['message'] = "Fundraiser creator not found.";
            header("Location: dashboard.php");
            exit();
        }
    } else {
        $_SESSION['message'] = "Campaign not found.";
        header("Location: dashboard.php");
        exit();
    }
}
?>
