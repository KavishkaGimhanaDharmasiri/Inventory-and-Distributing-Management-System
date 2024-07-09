<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Loutos</title>

    <link rel="stylesheet" href="css/productProductdetailstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
        integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

</head>

<body>
<?php

include 'Navibar2.php';
?>

<br><br><br>

    <div class="about" id="About">

        <div class="about_main">
            <div class="about_image">
                <div class="about_small_image">
                    <img src="Images/cable3.jpg" class="productImage" onclick="functio(this)">
                    <img src="Images/cable4.jpg" class="productImage" onclick="functio(this)">
                    <img src="Images/cable3.jpg" class="productImage" onclick="functio(this)">
                    <img src="Images/cable3.jpg" class="productImage" onclick="functio(this)">
                </div>

                <div class="image_contaner">
                    <img src="Images/cable5.jpg" id="imagebox" >
                </div>
            </div>
        </div>

        <script>
            function functio(small){
                var full = document.getElementById("imagebox")
                full.src = small.src
            }
        </script>
        
    </div>
        
        
        <div class="product-content">
            <form action="" method="post" id="form" class="form">
            <h2 class="product-title" name="productname">Cable</h2>
            <!-- <a href="#" class="product-link">Visit site</a> -->

            <div class="product-rating">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half'></i>
                <span>4.7(21)</span>
            </div>

            <div class="product-price">
                <p class="last-price">Old price: <span>Rs.170</span></p>
                <p class="new-price" name="price">New price: <span>Rs.150</span></p>
            </div>

            <div class="product-detail">
                <h2>About this item: </h2>
                <p>Product details of USB Extension Cable 1.5m 3m 5m Copper Male to Female 
                USB Extend Adapter Dual Shielding Transparent Blue Anti-interference.</p>
                <ul>
                    <li>Cable Type: Outdoor</li>
                    <li>Model: Yagi</li>
                    <li>Brand: No Brand</li>
                </ul>
            </div>

             <!-- <a href="Cart" class="btn">Shop Now<i class='bx bx-right-arrow-alt'></i></a> -->

            <div class="purchase-info">
                <input type="number" min="0" value="1" name="quantity"><br>
                <a href="SignUp.php" class=" ">
                    <button type="submit" class="btn" name="btn">
                   <h4> Add to Cart </h4>
                    <!-- <i class='bx bx-cart-alt'></i> -->
                    </button>
                </a>
                <!-- <button type="button" class="btn">Compare</button> -->
            </div>
            </form>
        </div>
    
    
    <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe>
</body>
</html>

<?php
if(isset($_POST['btn']))
{
    $id= $_POST['productname'];
    $name =$_POST['productname'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    
    //connect to DB
    $host='localhost';
    $username='root';
    $password="";
    $database="retail_website";

    $link=mysqli_connect($host,$username,$password,$database);

        if(!$link){
            die('could connect'.mysqli_error($link));
        }
        echo 'connected successfully';

    $usrer_insert="INSERT INTO cart(id,product_name,price,quantity) VALUES ('$id','$name', '$price', '$quantity')";


        if ($link->query($usrer_insert) === TRUE) {
        echo "New record created successfully";
        } else {
        echo "Error: " . $usrer_insert . "<br>" . $link->error;
    }   
    
    mysqli_close($link);
} 
?>