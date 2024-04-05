<?php
// Database connection parameters
$servername = "localhost"; // MySQL server hostname
$username = "root"; // MySQL username
$password = ""; // MySQL password
$dbname = "qr_db"; // Name of the database

try {
    // Create a new PDO instance to establish a connection to the database
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    // Set PDO error mode to throw exceptions
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // If connection fails, catch the exception and display an error message
    echo "Connection failed: " . $e->getMessage();
}
?>
