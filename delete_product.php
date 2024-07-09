<?php
include('connection.php');

// Check if the product_id is set in the POST request and is numeric
if(isset($_POST['product_id']) && is_numeric($_POST['product_id'])){
    $product_id = (int) $_POST['product_id'];

    // Prepare the update query using prepared statements
    $query = "UPDATE Product SET is_deleted = TRUE WHERE product_id = ? LIMIT 1";
    $stmt = mysqli_prepare($conn, $query);

    // Bind the parameter
    mysqli_stmt_bind_param($stmt, 'i', $product_id);

    // Execute the statement
    if(mysqli_stmt_execute($stmt)){
        // Check if any row was actually updated
        if(mysqli_stmt_affected_rows($stmt) > 0) {
            // Redirect back to the view page
            header("Location: viewProducts.php");
            exit;
        } else {
            echo "No product found with the given ID.";
        }
    } else {
        echo "Failed to mark the product as deleted. Error: " . mysqli_error($conn);
    }

    // Close the statement
    mysqli_stmt_close($stmt);
} else {
    echo "Invalid request.";
}
?>
