<?php
include('connection.php');

// Check if the supplierId is set in the POST request and is numeric
if(isset($_POST['supplierId']) && is_numeric($_POST['supplierId'])){
    $supplierId = (int) $_POST['supplierId'];

    // Prepare the update query using prepared statements
    $query = "UPDATE suppliers SET is_deleted = TRUE WHERE supplierId = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, 'i', $supplierId);

    // Execute the statement
    if(mysqli_stmt_execute($stmt)){
        // Check if any row was actually updated
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect back to the view page
            header("Location: viewSuppliers.php");
            exit;
        } else {
            echo "No supplier found with the given ID.";
        }
    } else {
        echo "Failed to mark the supplier as deleted. Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
