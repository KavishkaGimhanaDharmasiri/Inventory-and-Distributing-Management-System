<?php
session_start(); // Start the session

if (!isset($_SESSION['email'])) {
    die("Session variable 'email' is not set.");
}
$un = $_SESSION['email'];

// Database connection details
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website';

// Create a connection to the database
$conn = new mysqli($host, $username, $password, $database);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT userid FROM user_details WHERE email = ?";

// Prepare the statement
$stmt = $conn->prepare($query);

if (!$stmt) {
    die("Prepare failed: " . $conn->error);
}

// Bind the email parameter
$stmt->bind_param("s", $un);

// Execute the query
$stmt->execute();

// Get the result set
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    // Fetch the result as an associative array
    $row = $result->fetch_assoc();
    $userid = $row['userid'];
    //echo "User ID: " . $userid;



				$query = "SELECT cus_id FROM card_details WHERE cus_id = ?";
				$stmt = $conn->prepare($query);

				if (!$stmt) {
				    die("Prepare failed: " . $conn->error);
				}
				// Bind the cus_id parameter
				$stmt->bind_param("s", $userid );
				// Execute the query
				$stmt->execute();
				// Get the result set
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
				    // Fetch the result as an associative array
				    $row = $result->fetch_assoc();
				    $retrieved_cus_id = $row['cus_id'];
				   // echo "Customer ID: " . $retrieved_cus_id;




								$query = "SELECT cus_id FROM shippingdetails WHERE cus_id = ?";
								$stmt = $conn->prepare($query);
								if (!$stmt) {
								    die("Prepare failed: " . $conn->error);
								}
								// Bind the cus_id parameter
								$stmt->bind_param("s", $userid );
								// Execute the query
								$stmt->execute();
								// Get the result set
								$result = $stmt->get_result();

								if ($result->num_rows > 0) {
								    // Fetch the result as an associative array
								    $row = $result->fetch_assoc();
								    $retrieved_cus_id = $row['cus_id'];
								    //echo "Customer ID: " . $retrieved_cus_id;
								    header("Location: success.php");
								    


		} else {
   			 header("Location: carddetails.php");
		}
	} else {
   		 header("Location: carddetails.php");
	}

} else {
    header("Location: carddetails.php");
}

















$stmt->close(); // Close the prepared statement
$conn->close(); // Close the database connection
?>
