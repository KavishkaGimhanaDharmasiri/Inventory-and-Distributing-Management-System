<?php
$password = 'lotus';

// Hash the password using bcrypt algorithm
$hashedPassword = password_hash($password, PASSWORD_BCRYPT);

// Output the hashed password
echo $hashedPassword;
?>
