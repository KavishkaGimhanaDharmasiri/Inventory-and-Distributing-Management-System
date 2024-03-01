<?php
session_start();
$userId = $_SESSION['user_ID'];
    echo $userId;

   include("db_connection.php");
if (isset($_SESSION['user_ID'])) {
       include("db_connection.php");


    if ($connection->connect_error) {
        die("Connection failed: " . $connection->connect_error);
    }


    $sql = "SELECT * FROM users WHERE user_id = $userId";
    $result = $connection->query($sql);

    if ($result->num_rows > 0) {
        $userData = $result->fetch_assoc();
        echo json_encode($userData);
    } else {
        echo json_encode(['error' => 'User not found']);
    }

    $conn->close();
} else {
    echo json_encode(['error' => 'User not logged in']);
}
?>
