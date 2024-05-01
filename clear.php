<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

// Redirect to a confirmation page or any other page
header("Location: index.php");
exit();
?>