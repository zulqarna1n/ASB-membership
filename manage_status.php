<?php
// Securely check if the user is logged in as an admin
include 'auth.php'; 
include 'config.php';

// Capture and sanitize the ID and Action from the URL
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($id > 0 && $action === 'delete') {
    try {
        // Use a prepared statement to prevent SQL injection
        $stmt = $pdo->prepare("DELETE FROM members WHERE id = ?");
        $stmt->execute([$id]);
        
        // Optional: You could add a success message to a session here
    } catch (PDOException $e) {
        // Log the error silently so users don't see DB structure
        error_log("Delete failed: " . $e->getMessage());
    }
}

// Always redirect back to the main registry to prevent a blank page
header("Location: index.php");
exit();
?>