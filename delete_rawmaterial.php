<?php
include('connection.php');

// Check if the RawMaterialsID is set in the POST request and is numeric
if(isset($_POST['RawMaterialsID']) && is_numeric($_POST['RawMaterialsID'])){
    $RawMaterialsID = (int) $_POST['RawMaterialsID'];

    // Prepare the update query using prepared statements
    $query = "UPDATE rawmaterials SET is_deleted = TRUE WHERE RawMaterialsID = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, 'i', $RawMaterialsID);

    // Execute the statement
    if(mysqli_stmt_execute($stmt)){
        // Check if any row was actually updated
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect back to the view page
            header("Location: ViewRaw.php");
            exit;
        } else {
            echo "No raw material found with the given ID.";
        }
    } else {
        echo "Failed to mark the raw material as deleted. Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
