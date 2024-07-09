<?php
include('connection.php');

// Check if the OutsourcerID is set in the POST request and is numeric
if(isset($_POST['OutsourcerID']) && is_numeric($_POST['OutsourcerID'])){
    $OutsourcerID = (int) $_POST['OutsourcerID'];

    // Prepare the update query using prepared statements
    $query = "UPDATE outsourcer SET is_deleted = TRUE WHERE OutsourcerID = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, 'i', $OutsourcerID);

    // Execute the statement
    if(mysqli_stmt_execute($stmt)){
        // Check if any row was actually updated
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect back to the view page
            header("Location: viewOutsourcer.php");
            exit;
        } else {
            echo "No outsourcer found with the given ID.";
        }
    } else {
        echo "Failed to mark the outsourcer as deleted. Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
