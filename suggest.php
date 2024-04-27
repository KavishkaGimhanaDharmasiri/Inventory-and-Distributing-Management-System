<?php
require_once "db_connection.php";
session_start(); // Include your database connection configuration file

// Check if input is provided
if (isset($_POST['input'])) {
    $input = $_POST['input'];

    try {
        // Prepare and execute the query to retrieve user suggestions
        $stmt = $pdo->prepare("SELECT product_id,main_cat,sub_cat FROM product WHERE sub_cat LIKE ?");
        $stmt->execute(["%" . $input . "%"]);

        // Fetch suggestions
        $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format the suggestions
        $formattedSuggestions = array();
        foreach ($suggestions as $row) {
            $formattedSuggestions[] = $row['sub_cat'] . " (" . $row['main_cat'] . ")";
            $_SESSION['product_id'] = $row['product_id'];
        }
        $_SESSION['product_id'] = $row['product_id'];
        // Return the formatted suggestions as JSON
        echo json_encode($formattedSuggestions);
    } catch (PDOException $e) {
        // Handle database errors
        echo json_encode(array('error' => 'Database error: ' . $e->getMessage()));
    }
} else {
    // If no input is provided, return an empty array
    echo json_encode(array());
}
$_SESSION['product_id'] = $row['product_id'];
