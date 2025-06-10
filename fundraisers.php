<?php
// fundraisers.php
include('db.php'); // Include the database connection

// Query to fetch active fundraisers
$sql = "SELECT * FROM fundraisers";

// Execute the query
$result = $conn->query($sql);

// Initialize an empty array to store fundraisers
$fundraisers = [];

if ($result->num_rows > 0) {
    // Fetch all the fundraisers as an associative array
    while ($row = $result->fetch_assoc()) {
        $fundraisers[] = $row;
    }
}

// Close the database connection
$conn->close();
?>
