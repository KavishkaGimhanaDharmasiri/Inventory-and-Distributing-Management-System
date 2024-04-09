<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Product_Details</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="css/productdetails.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
  </head>
  <body>
    <?php

include 'Navibar.php';
?>
<div>
    <div class = "card-wrapper">
      <div class = "card">
        <!-- card left -->
        <div class="about" id="About">

        <div class="about_main">
            <div class="about_image">
                <div class="about_small_image">
                    <img src="Images/Cables/cable3.jpg" class="productImage" onclick="functio(this)">
                    <img src="Images/Cables/cable4.jpg" class="productImage" onclick="functio(this)">
                    <img src="Images/Cables/cable5.jpg" class="productImage" onclick="functio(this)">
                    <img src="Images/Cables/cable3.jpg" class="productImage" onclick="functio(this)">
                </div>

                <div class="image_contaner">
                    <img src="Images/Cables/cables2.jpg" id="imagebox" >
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
        
        <!-- card right -->
        <div class = "product-content">
          <h2 class = "product-title">nike shoes</h2>
          <div class = "product-rating">
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star"></i>
            <i class = "fas fa-star-half-alt"></i>
            <span>4.7(21)</span>
          </div>

          <div class = "product-price">
            <p class = "new-price">Price: <span>$249.00 (5%)</span></p>
          </div>

          <div class = "product-detail">
         
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Illo eveniet veniam tempora fuga tenetur placeat sapiente architecto illum soluta consequuntur, aspernatur quidem at sequi ipsa!</p>
            <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. Consequatur, perferendis eius. Dignissimos, labore suscipit. Unde.</p>

          </div>

          <div class = "purchase-info">
            <input type = "number" min = "0" value = "1"><br>
            <a href="SignIn.php"><button type = "button" class = "btn">Add to Cart
               <i class = "fas fa-shopping-cart"></i>
            </button></a>
          </div>
        </div>
    <script src="script.js"></script>
</div>

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