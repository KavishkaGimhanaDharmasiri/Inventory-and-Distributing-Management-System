<?php
include('connection.php'); // Include the connection file

if(isset($_POST['suppilerName']) && isset($_POST['contactNumber']) && isset($_POST['address'])) {
    // Retrieve form data
    $supplierName = $_POST['suppilerName'];
    $contactNumber = $_POST['contactNumber'];
    $address = $_POST['address'];

    // Prepare SQL statement
    $sql = "INSERT INTO suppliers (supplierName,contactNumber,address) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $supplierName, $contactNumber, $address);

    // Execute the statement
    if ($stmt->execute()) {
        // Data inserted successfully
        echo '<script type ="text/JavaScript">';  
        header("Location: viewSuppliers.php");
    } else {
        // Error occurred while inserting data
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>