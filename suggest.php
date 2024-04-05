<?php
require_once "db_connection.php"; // Include your database connection configuration file

// Check if input is provided
if (isset($_POST['input'])) {
    $input = $_POST['input'];

    try {
        // Prepare and execute the query to retrieve user suggestions
        $stmt = $pdo->prepare("SELECT user_id, firstName, LastName, state FROM users WHERE CONCAT(firstName, ' ', LastName) LIKE ?");
        $stmt->execute(["%" . $input . "%"]);
        
        // Fetch suggestions
        $suggestions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Format the suggestions
        $formattedSuggestions = array();
        foreach ($suggestions as $row) {
            $formattedSuggestions[] = $row['user_id'] . ": " . $row['firstName'] . " " . $row['LastName'] . " (" . $row['state'] . ")";
        }

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
?>
