<?php
session_start();

// Check if the selectedIndexes parameter is set in the POST request
if (isset($_POST['selectedIndexes'])) {
    // Decode the JSON string sent from JavaScript to get the selected indexes
    $selectedIndexes = json_decode($_POST['selectedIndexes']);

    // Check if $_SESSION['order_details'] is set and is an array
    if (isset($_SESSION['order_details']) && is_array($_SESSION['order_details'])) {
        // Loop through the selected indexes and unset the corresponding elements from $_SESSION['order_details']
        foreach ($selectedIndexes as $index) {
            // Check if the index exists in $_SESSION['order_details']
            if (isset($_SESSION['order_details'][$index])) {
                // Remove the element at the specified index
                unset($_SESSION['order_details'][$index]);
            }
        }

        // Optionally, reindex the array after unsetting elements
        $_SESSION['order_details'] = array_values($_SESSION['order_details']);

        // Send a response back to JavaScript indicating success
        echo 'Order details updated successfully';
    } else {
        // Send a response back to JavaScript indicating failure
        echo 'Error: $_SESSION[\'order_details\'] is not set or is not an array';
    }
} else {
    // Send a response back to JavaScript indicating failure
    echo 'Error: selectedIndexes parameter is not set in the POST request';
}
?>
