<?php

$dbhost = 'fdb1034.awardspace.net';
$dbname = '4435655_lotus';
$dbuser = '4435655_lotus';
$dbpass = 'WEhfT7?dW#y*RZ8';

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