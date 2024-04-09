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

</head>

<body>
<?php

include 'Navibar.php';
?>
    <section class="products" id="products">
         <div class="products-container">

         <?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'invenroty'; // corrected spelling of 'inventory'

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM product WHERE main_cat='Antenna and Accessories'"; // corrected table name 'product'

$result = $conn->query($sql);

if ($result) { // Check if the query was successful
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $product_id = $row["product_id"];
            $main_cat = $row["main_cat"];
            $sub_cat = $row["sub_cat"];
            $quantity = $row["stock"];
            $price = $row["cashPrice"];
            $description = $row["productType"];
            $img = $row["image"];
            $discount = $row["Discount"];

            echo '<div class="box">
                    <a href="ProductDetails.php">
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
