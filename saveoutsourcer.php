<?php
include('connection.php');
 // Include the connection file

if(isset($_POST['SubmitButton'])) {
    // Retrieve form data
    $outsourcerName = $_POST['OutsourcerName'];
    $address = $_POST['Address'];
    $contactNumber = $_POST['ContactNumber'];

    // Prepare SQL statement
    $sql = "INSERT INTO outsourcer (OutsourcerName, Address, ContactNumber) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $outsourcerName, $address, $contactNumber);

    // Execute the statement
    if ($stmt->execute()) {
        // Data inserted successfully
        echo "Data inserted successfully.";

        header("Location: viewOutsourcer.php");
        
    } else {
        // Error occurred while inserting data
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    header('viewOutsourcer.php');

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();


?>