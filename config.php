<?php
// Database credentials
$host = 'localhost';
$db   = 'asbm_db';
$user = 'root'; 
$pass = '';     

try {
    // Establishing connection with UTF-8 to support all characters
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8", $user, $pass);
    
    // Error Mode: Throws exceptions so our 'auth.php' and 'index.php' can handle issues gracefully
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Fetch Mode: Sets default to Associative Array for cleaner code (e.g., $row['full_name'])
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
    // Security: Disables emulated prepared statements to use real prepared statements
    $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

} catch (PDOException $e) {
    // In production, you might want to log this instead of 'die' to hide DB details from users
    die("The ASB Portal is currently offline. Please contact the administrator.");
}
?>