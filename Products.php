<?php
session_start();
$un=$_SESSION['email'];
// var_dump($_SESSION);
// var_dump($_SESSION);
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Loutos</title>

    <link rel="stylesheet" href="css/styledash.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    div.label1 {
   position: absolute;
    right: 60px;
    color: red;
    transform: rotate(45deg);
    top: 120px;
    background-color: pink;
    -webkit-transform: rotate(30deg);

    font-size: 25px;
    font-weight: bold;
}
div.label2 {
   position: absolute;
    right: 60px;
    color: orange;
    transform: rotate(45deg);
    top: 120px;
    background-color: yellow;

    -webkit-transform: rotate(30deg);

    font-size: 25px;
    font-weight: bold;
}


</style> 
</head>

<body class="product-background">

<?php

include 'Navibar.php';
?>

    <section class="products" id="products">
         <div class="products-container">

<?php

$catnum=$_GET['categoryid'];
$_SESSION['categoryid']=$catnum;
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website'; // corrected spelling of 'inventory'

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM product WHERE main_cat=$catnum";

$result = $conn->query($sql);

if ($result) { // Check if the query was successful
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row["product_id"];
            $main_cat = $row["main_cat"];
            $sub_cat = $row["sub_cat"];
            $stock=$row["stock"];
            $quantity = $row["stock"];
            $price = $row["cashPrice"];
            $description = $row["productType"];
            $img = $row["image"];
            $discount = $row["Discount"];

            echo '<div class="box">
                    <a href="Productdetails.php?categoryid='.$product_id.'">

                    <div class="image">
                             <img src="data:image;base64,' . base64_encode($img) . '" alt="">
                    </div> 
                             <h2>' . $sub_cat . '</h2>
                             <p>' . $main_cat . '</p>
                            <h3 class="price">Rs. ' . $price . '</h3>';

            if ($discount != '' && $discount != NULL)
             { // Added condition to check if discount exists
                echo '<span class="discount">' . $discount . '%</span></a>'; 
            }
//echo $stock;
            if ($stock==0)
             { 
                echo '<div class="label1">OUT OF STOCK !</div>'; 
            }

            if ($stock<=5 && $stock>0)
             { 
                echo '<div class="label2">LIMITED STOCK !</div>'; 
            }

            
            echo '
                </div>';
        }
    } else {
        echo "0 results";
    }
} else {
    echo "Query failed: " . $conn->error; // Error message if query fails
}

$conn->close(); // Close the database connection
?>

           
</div>

    </section>
   
    <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe>
</body>
