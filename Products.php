<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Loutos</title>

    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
<?php

include 'Header.php';
?>
    <section class="products" id="products">
         <div class="products-container">



        <?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website';

$conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $sql = "SELECT * FROM products";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    
         while ($row = $result->fetch_assoc()) {
            $product_id=$row["product_id"];
            $name=$row["product_name"];
            $price=$row["price"];
            $quantity=$row["quantity"];
            $description=$row["product_description"];
            $img=$row["image"];
            $discount=$row["discount"];

            echo '<div class="box">
                    <a href="ProductDetails.php">
                             <img src="Images/cat1.png" alt="">
                            <span>'.$name.'</span>
                            <h3 class="price">'.$price.'</h3>';
                                ?>

                    <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount"><?php echo$discount."%"; ?></span>

                <?php

                           echo '</a>
                            </div>';
        }
        
    } else {
    echo "0 results";
}
?>



       
            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat1.png" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat2.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat3.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat4.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat5.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat6.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat7.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat8.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

            <div class="box">
                <a href="/productProductdetails.html">
                    <img src="Images/cat9.jpg" alt="">
                <span>Holder</span>
                <h2></h2>
                <h3 class="price">Rs650</h3>
                <i class='bx bx-cart-alt'></i>
                <i class='bx bx-heart'></i>
                <span class="discount">-25%</span>
                </a>
            </div>

        </div>

    </section>
    <script src="css/productProductdetailstyle.css"></script>
    <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe>
</body>