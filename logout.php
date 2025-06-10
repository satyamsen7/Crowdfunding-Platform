<?php
session_start(); // Start the session to access session variables

// Destroy all session variables
session_destroy();

// Redirect to the admin login page
header("Location: index.php");
exit();
?>
