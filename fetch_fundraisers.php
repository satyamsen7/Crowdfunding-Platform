<?php
include 'db.php'; // Include database connection


// Get the selected category from the URL parameter
$category = isset($_GET['category']) ? mysqli_real_escape_string($conn, $_GET['category']) : 'all';

// Build the SQL query based on the category filter
$sql = "SELECT person_name, purpose, amount, photo, campaign_link, category FROM fundraisers";
if ($category != 'all') {
    $sql .= " WHERE category = '$category'";
}
$sql .= " ORDER BY campaign_id DESC";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="fundraiser-card">';
        echo '<img src="uploads/' . htmlspecialchars($row['photo']) . '" alt="Fundraiser Image">';
        // echo '<h3>' . htmlspecialchars($row['purpose']) . '</h3>';
        echo '<p>by ' . htmlspecialchars($row['person_name']) . '</p>';
        echo '<p><strong>₹' . number_format($row['amount']) . '</strong> raised</p>';
        echo '<p><strong>Category:</strong> ' . htmlspecialchars($row['category']) . '</p>';
        echo '<a href="view_campaign.php?campaign_link=' . urlencode($row['campaign_link']) . '">View Campaign</a>';
        echo '</div>';
    }
} else {
    echo '<p>No fundraisers available in this category.</p>';
}

$conn->close();
?>
