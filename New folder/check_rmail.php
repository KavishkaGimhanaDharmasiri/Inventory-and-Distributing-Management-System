<?php
// Include your database connection file
include("db_connection.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the email from the POST data
    $email = $_POST["email"];

    // Validate the email against the database
    $query = "SELECT email FROM users WHERE email='$email'";
    $result = mysqli_query($connection, $query);

    if ($result && mysqli_num_rows($result) == 1) {
        // Email is valid
        $response = array("isValid" => true);
    } else {
        // Email is not valid
        $response = array("isValid" => false);
    }

    // Send the JSON response
    header("Content-Type: application/json");
    echo json_encode($response);

    // Close the database connection
    mysqli_close($connection);
}
?>
