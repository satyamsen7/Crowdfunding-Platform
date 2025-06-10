<?php
// thankyou.php
if (isset($_GET['campaign_link'])) {
    $campaign_link = $_GET['campaign_link'];
} else {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
  
</head>
<body>
    <div class="container">
        <h1>Thank you for your donation!</h1>
        <p>Your donation has been successfully processed.</p>
        <a href="view_campaign.php?campaign_link=<?php echo urlencode($campaign_link); ?>">Back to Campaign</a>
    </div>
</body>
</html>

