<?php
include 'db.php'; // Include database connection

// Add your Razorpay key
$razorpay_key = "rzp_test_WvWGQS4rfAg1Qc"; // Replace with your actual Razorpay key

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $campaign_link = $_POST['campaign_link'];
    $amount = $_POST['amount'];

    // Check if the campaign exists
    $stmt = $conn->prepare("SELECT * FROM fundraisers WHERE campaign_link = ?");
    $stmt->bind_param("s", $campaign_link);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $fundraiser = $result->fetch_assoc();

        // Fetch total amount collected so far
        $stmt_donations = $conn->prepare("SELECT SUM(amount) as total_collected FROM donations WHERE campaign_link = ?");
        $stmt_donations->bind_param("s", $campaign_link);
        $stmt_donations->execute();
        $result_donations = $stmt_donations->get_result();
        $donation_data = $result_donations->fetch_assoc();
        $total_collected = $donation_data['total_collected'] ?? 0;

        // Calculate remaining amount needed for the campaign
        $amount_needed = $fundraiser['amount'];
        $remaining_amount = $amount_needed - $total_collected;

        // Server-side validation to ensure donation amount doesn't exceed remaining
        if ($amount > $remaining_amount) {
            echo "You cannot donate more than the remaining amount required.";
        } else {
            // Razorpay's JS SDK to initiate the payment process
            ?>
            <!DOCTYPE html>
            <html>
            <head>
                <title>Donate</title>
                <script src="https://checkout.razorpay.com/v1/checkout.js"></script>
            </head>
            <body>
                <h2>Donate to <?php echo htmlspecialchars($fundraiser['person_name']); ?>'s Campaign</h2>

                <script>
                    var options = {
                        "key": "<?php echo $razorpay_key; ?>", // Razorpay Key ID
                        "amount": <?php echo $amount * 100; ?>, // Amount in paisa (e.g., ₹1000 is 100000 paisa)
                        "currency": "INR",
                        "name": "Campaign Donation",
                        "description": "Donation for " + "<?php echo htmlspecialchars($fundraiser['person_name']); ?>",
                        "image": "https://your-logo-url.com/logo.png", // Optional logo
                        "handler": function (response) {
                            alert("Payment successful! Payment ID: " + response.razorpay_payment_id);

                            // Send donation details to the server
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "save_donation.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function () {
                                if (xhr.readyState === 4 && xhr.status === 200) {
                                    console.log(xhr.responseText);
                                    alert('Thank you for your donation!');
                                    // Optionally, redirect the user to a thank-you page
                                    window.location.href = "thank_you.php";
                                }
                            };
                            xhr.send("campaign_link=<?php echo $campaign_link; ?>&amount=<?php echo $amount; ?>&payment_id=" + response.razorpay_payment_id);
                        },
                        "modal": {
                            "ondismiss": function(){
                                alert('Payment failed or canceled. Please try again.');
                                // Optionally redirect back to the campaign page
                                window.location.href = "view_campaign.php?campaign_link=<?php echo urlencode($campaign_link); ?>";
                            }
                        }
                    };

                    var rzp1 = new Razorpay(options);
                    rzp1.open();
                </script>
            </body>
            </html>
            <?php
        }
    } else {
        echo "Campaign not found.";
    }
} else {
    echo "Invalid request method.";
}
?>
