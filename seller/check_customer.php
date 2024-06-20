<?php
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php"); // Include your database connection script
// Include your database connection script

if (isset($_POST['telephone']) || isset($_POST['email'])) {
    $telephone = isset($_POST['telephone']) ? $_POST['telephone'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';

    $query = "SELECT * FROM users WHERE telphone_no='$telephone' OR email='$email'";
    $result = mysqli_query($connection, $query);

    if (mysqli_num_rows($result) > 0) {
        echo json_encode(['customer_exists' => true]);
    } else {
        echo json_encode(['customer_exists' => false]);
    }
} else {
    echo "Data is not set";
}
