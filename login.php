<?php
// Assuming your database connection is in db_connection.php
include("db_connection.php");

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get the username and password from the Flutter app
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Validate the user credentials
    $query = "SELECT * FROM login WHERE username = '$username' AND password = '$password'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Check if a matching record is found
        if (mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            // Send a success response
            echo json_encode(array(
                'status' => 'success',
                'user_id' => $row['user_id'],
                'username' => $row['username'],
                'state' => $row['state'],
                'route_id' => $row['route_id']
            ));
        } else {
            // Send an error response if credentials are invalid
            echo json_encode(array(
                'status' => 'error',
                'message' => 'Invalid username or password.'
            ));
        }
    } else {
        // Send an error response for database query issues
        echo json_encode(array(
            'status' => 'error',
            'message' => 'Database query failed.'
        ));
    }

    // Close the database connection
    mysqli_close($connection);
} else {
    // Send an error response if the request method is not POST
    echo json_encode(array(
        'status' => 'error',
        'message' => 'Invalid request method.'
    ));
}
?>
