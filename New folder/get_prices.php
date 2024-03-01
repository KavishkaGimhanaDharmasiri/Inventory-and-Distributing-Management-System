<?php
// get_prices.php
include("db_connection.php");

$mainCategory = $_GET['mainCategory'];
$subCategory = $_GET['subCategory'];

$query = "SELECT cashPrice, checkPrice, creditPrice FROM product WHERE main_cat = '$mainCategory' AND sub_cat = '$subCategory'";
$result = mysqli_query($connection, $query);

if (!$result) {
    die("Database query failed: " . mysqli_error($connection));
}

$prices = mysqli_fetch_assoc($result);
echo json_encode($prices);

mysqli_close($connection);
?>
