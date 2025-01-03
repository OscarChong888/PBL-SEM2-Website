<?php
session_start(); // Start the session

// Check if the logout button is pressed
if (isset($_POST['logout'])) {
    // Destroy all session data
    session_unset(); 
    session_destroy(); 
    
    // Redirect to the login page (or home page)
    header('Location: index.html');
    exit();
}
?>
