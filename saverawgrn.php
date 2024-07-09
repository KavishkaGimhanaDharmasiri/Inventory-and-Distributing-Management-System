<?php
include('connection.php'); // Include the connection file

if(isset($_POST['rawmaterials']) && isset($_POST['supplier']) && isset($_POST['Quantity'])) {
    // Retrieve form data
    $rawMaterialsID = $_POST['rawmaterials'];
    $supplierID = $_POST['supplier'];
    $quantity = $_POST['Quantity'];

    // Check if the combination of RawMaterialsID and supplierId already exists
    $checkQuery = "SELECT COUNT(*) AS count FROM rawgrn WHERE RawMaterialsID = ? AND supplierId = ?";
    $stmt = $conn->prepare($checkQuery);
    $stmt->bind_param("ii", $rawMaterialsID, $supplierID);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $count = $row['count'];
    $stmt->close();

    if ($count > 0) {
        // Combination already exists, update rawQty
        $updateQuery = "UPDATE rawgrn SET rawQty = rawQty + ? WHERE RawMaterialsID = ? AND supplierId = ?";
        $stmt = $conn->prepare($updateQuery);
        $stmt->bind_param("iii", $quantity, $rawMaterialsID, $supplierID);
        if ($stmt->execute()) {
            // Quantity updated successfully
            echo "Quantity updated successfully.";
            header("Location: ViewRawStocks.php");

        } else {
            // Error occurred while updating qty
            echo "Error: " . $updateQuery . "<br>" . $conn->error;
        }
    } else {
        // Combination does not exist, insert new record
        $rawMaterialNameQuery = "SELECT RawMaterialstName FROM rawmaterials WHERE RawMaterialsID = ?";
        $stmt = $conn->prepare($rawMaterialNameQuery);
        $stmt->bind_param("i", $rawMaterialsID);
        $stmt->execute();
        $rawMaterialNameResult = $stmt->get_result();
        $rawMaterialName = "";
        if ($row = $rawMaterialNameResult->fetch_assoc()) {
            $rawMaterialName = $row['RawMaterialstName'];
        }
        $stmt->close();

        $supplierNameQuery = "SELECT supplierName FROM suppliers WHERE supplierId = ?";
        $stmt = $conn->prepare($supplierNameQuery);
        $stmt->bind_param("i", $supplierID);
        $stmt->execute();
        $supplierNameResult = $stmt->get_result();
        $supplierName = "";
        if ($row = $supplierNameResult->fetch_assoc()) {
            $supplierName = $row['supplierName'];
        }
        $stmt->close();

        // Prepare SQL statement
        $insertQuery = "INSERT INTO rawgrn (RawMaterialsID, RawMaterialstName, supplierId, supplierName, rawQty) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($insertQuery);
        $stmt->bind_param("isssi", $rawMaterialsID, $rawMaterialName, $supplierID, $supplierName, $quantity);

        // Execute the statement
        if ($stmt->execute()) {
            // Data inserted successfully
            echo "Raw Materials Good Received Note saved successfully.";
            header("Location: ViewRawStocks.php");
        } else {
            // Error occurred while inserting data
            echo "Error: " . $insertQuery . "<br>" . $conn->error;
        }
        $stmt->close();
    }
}

// Close connection
$conn->close();
?>
