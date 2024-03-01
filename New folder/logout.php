<?php
// Start the session
session_start();

// Unset all session variables
$_SESSION = array();

// Destroy the session
session_destroy();

//header("Location: index.php");

// Send a response to indicate successful logout
//echo "Logout successful";
?>
