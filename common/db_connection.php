<?php

$dbhost = 'localhost';
$dbname = 'lotus';
$dbuser = 'root';
$dbpass = '';

// Create a PDO connection
try {
    $pdo = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("PDO Connection failed: " . $e->getMessage());
}

// Provide the existing MySQLi connection for compatibility
$connection = mysqli_connect($dbhost, $dbuser, $dbpass, $dbname);

if (!$connection) {
    die("MySQLi Connection failed: " . mysqli_connect_error());
}
?>