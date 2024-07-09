<?php
session_start();
$product_id=$_GET['id'];
$cus_id=$_SESSION['user_id'];
if(isset($_GET['id'])){

        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'retail_website';

        $conn = new mysqli($host, $username, $password, $database);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }


       
        $sql = "DELETE FROM cart  WHERE cus_id = $cus_id AND product_id=$product_id";
        echo  $sql;

       if ($conn->query($sql) === TRUE) {
            echo "Record deleted successfully";
        } else {
            echo "Error deleting record: " . $conn->error;
        }

// Close the database connection
$conn->close();

       header("Location: cart.php");
        exit; // Ensure script stops executing after redirect
}
?>
