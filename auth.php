<?php
// Check if a session is already active before starting one
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * ASB Admin Authorization Guard
 * This script ensures that only users with an active 'asb_admin' session 
 * can access the page. 
 */
if (!isset($_SESSION['asb_admin']) || $_SESSION['asb_admin'] !== true) {
    // Force redirect to login if not authorized
    header("Location: login.php");
    exit();
}

// If code reaches this point, the user is authorized.
?>