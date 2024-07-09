<?php
// saveProduct.php

// Include the database connection
include('connection.php');

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $maincategory = $_POST['maincategory'];
    $productName = $_POST['productName'];
    $costPrice = $_POST['costPrice'];
    $creditprice = $_POST['creditprice'];
    $chequeprice = $_POST['chequeprice'];
    $sellingPrice = $_POST['sellingPrice'];
    $supplier = $_POST['supplier'];
    $productType = $_POST['productTpye'];

    // SQL query to insert data into the database
    $query = "INSERT INTO product (main_cat, sub_cat, costPrice, creditPrice, checkPrice, cashPrice, productType, supplierId)
              VALUES ('$maincategory', '$productName', '$costPrice', '$creditprice', '$chequeprice', '$sellingPrice', '$productType', '$supplier')";

    // Execute query
    if (mysqli_query($conn, $query)) {
        echo "Product saved successfully.";
        header("Location: viewProducts.php");
    } else {
        echo "Error: " . $query . "<br>" . mysqli_error($conn);
    }

    // Close connection
    mysqli_close($conn);
}
?>
