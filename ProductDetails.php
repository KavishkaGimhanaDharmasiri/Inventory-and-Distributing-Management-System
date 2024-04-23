<?php
session_start();
?>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product_Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/productdetails.css">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" /> -->
  </head>
  <body >
<?php
include 'Navibar.php';
?>

<!-- <div class="products-container"> -->

<?php
$productid=$_GET['categoryid'];

 $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'retail_website';

    $conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM product WHERE product_id=$productid";
    $result = $conn->query($sql);

    if ($result) {
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

                $_SESSION['product_id'] = $product_id; // Store product ID in session

                echo '<div class="card-wrapper">
                        <div class="card">
                            <div class="about" id="About"> 
                                <div class="about_main">
                                    <div class="about_image">
                                        <div class="image_contaner">
                                            <img src="data:image;base64,' . base64_encode($img) . '" id="imagebox">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="product-content">
                                <h2 class="product-title">' . $sub_cat . '</h2>';
                                
                if ($discount != '' && $discount != NULL) { 
                    $p = ($discount * $price) / 100;
                    $newprice = $price - $p;
                    echo '<div class="last-product-price">
                            <p class="last-price">Old Price: <span>Rs. ' . $price . '</span></p>
                          </div>
                          <div class="new-product-price">
                            <p class="new-price">Price: <span>Rs. ' . $newprice . '</span></p>
                          </div>';
                } else {
                    echo '<div class="new-product-price">
                            <p class="new-price">Price: <span>Rs. ' . $price . '</span></p>

                          </div>';
                }

                echo '<div>
                        <h4>'.$description.'</h4>
                      </div>'; // Close the divs properly
            }
        } else {
            echo "0 results";
        }
    } else {
        echo "Query failed: " . $conn->error;
    }

    $conn->close(); // Close the database connection
// Close the database connection
?>
   <div class = "product-detail">
                          
                        </div>
                        <form action="SignIn.php" method="post" id="form">
                        <div class = "purchase-info">
                           <input type="number" min="0" value="1" name="quantity" id="quantity"><br>
                           <a href="SignIn.php?product_id='.$product_id.'"><button type="submit" name="btn" class="btn">Add to Cart <i class="fas fa-shopping-cart"></i></button></a>
                         </div></form>
                        </div></div></div>

<?php
if(isset($_POST['btn'])){
    $quantity = $_POST['quantity'];
    $_SESSION['quantity'] = $quantity;
 }
include 'Sameproducts.php';
?> 
</body>
 
</html>

