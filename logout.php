<?php
// Start the session
session_start();  // This initializes the session to access the current session data

// Unset all session variables
session_unset();  // This clears all the session variables

// Destroy the session
session_destroy();  // This destroys the session, effectively logging out the user

// Redirect to home page
header("Location: index.php");  // Redirects to the index.php page (or homepage)
exit();  // Ensures the script stops executing after the redirect
?>
