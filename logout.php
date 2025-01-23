<?php
session_start();

// Destroy the session
session_unset(); // Remove all session variables
session_destroy(); // Destroy the session itself

// Redirect to the login page
header('Location: index.php');
exit();
?>
