<?php
include('connection.php'); // Include the connection file

if(isset($_POST['save_button'])) {
    // Retrieve form data
    $rawMaterialName = $_POST['RawMaterialstName'];
    $costPrice = $_POST['CostPrice'];
    $supplierId = $_POST['supplier'];
    $unit = $_POST['Unit'];

    // Fetch supplier name based on supplier ID
    $getSupplierNameQuery = "SELECT supplierName FROM suppliers WHERE supplierId = ?";
    $stmt = $conn->prepare($getSupplierNameQuery);
    $stmt->bind_param("i", $supplierId);
    $stmt->execute();
    $result = $stmt->get_result();
    $supplierName = "";
    if ($row = $result->fetch_assoc()) {
        $supplierName = $row['supplierName'];
    }
    $stmt->close();

    // Prepare SQL statement
    $sql = "INSERT INTO rawmaterials (RawMaterialstName, CostPrice, SupplierId, SupplierName, Unit) VALUES (?, ?, ?, ?, ?)";

    // Prepare and bind parameters
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssiss", $rawMaterialName, $costPrice, $supplierId, $supplierName, $unit);

    // Execute the statement
    if ($stmt->execute()) {
        // Data inserted successfully
        echo "Raw material added successfully.";
        header("Location: viewRaw.php");
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
