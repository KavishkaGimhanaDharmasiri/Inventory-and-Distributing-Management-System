<?php
include('connection.php'); // Include the connection file




if(isset($_POST['suppilerName']) && isset($_POST['contactNumber']) && isset($_POST['address'])) {

    // Retrieve form data
    $supplierName = $_POST['suppilerName'];
    $contactNumber = $_POST['contactNumber'];
    $address = $_POST['address'];

    $sql = "select * from suppliers where supplierName = '$supplierName' and contactNumber = '$contactNumber' and address = '$address'";
    $result = mysqli_query($conn, $sql );

    if (mysqli_num_rows($result) >= 1) {
        echo '<script>alert("Supplier Already Exist")</script>';
        echo '<script>window.location.href="AddSuppilers.php"</script>';
    } else {
    

    
    // Prepare SQL statement
    $sql = "INSERT INTO suppliers (supplierName,contactNumber,address) VALUES (?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sss", $supplierName, $contactNumber, $address);

    // Execute the statement
    if ($stmt->execute()) {
        // Data inserted successfully
        echo '<script>alert("Add Supplier Sucessfully")</script>';  
        header("Location: viewSuppliers.php");
    } else {
        // Error occurred while inserting data
       // echo "Error: " . $sql . "<br>" . $conn->error;
        echo '<script>alert("Not Sucessfully")</script>';

    }

    // Close statement
    $stmt->close();

} // End of if (duplicat value)

        
}// else end if

// Close connection
$conn->close();
?>