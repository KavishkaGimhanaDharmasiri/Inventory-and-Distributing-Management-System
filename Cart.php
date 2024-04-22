
<?php
session_start();
include 'Navibar.php';
$un=$_SESSION['usrname'];
$p_id=$_SESSION['p_id'];
?>
<html>

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-compatible" content="IE=edge">
    <meta name="viewpoint" content="width=device-width, initial-scale=1.0">
    <title>Loutos</title>

    <link rel="stylesheet" href="css/cartstyle.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>

<body class="cart-body">

<br><br>
    <div class="wrapper">
        <h1>shopping Cart</h1>
        <div class="cart">
            <div class="shop">
                

<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website'; // corrected spelling of 'inventory'

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
 $stmt = $conn->prepare("CALL get_cart_details(?)");
$stmt->bind_param("s", $un); // 'i' indicates integer type for the parameter
$stmt->execute();

// Get the result set
$result = $stmt->get_result();


$total=0;
$shipping=200;

if ($result) { // Check if the query was successful
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {

            $sub_cat = $row["sub_cat"];
            $quantity = $row["quantity"];
            $price = $row["cashPrice"];
            $img = $row["image"];
            $discount = $row["Discount"];

            $subtotal=$price*$quantity;

            echo '<div class="box">
                    <input type="checkbox" name="item1_quantity[]" value="1" class="checkbox">
                    <img src="data:image;base64,'. base64_encode($img) .'" alt="">
                    <div class="content">
                        <h3>'.$sub_cat.'</h3>
                        <h4>Price: Rs. '.$subtotal.'</h4>
                        <p class="unit">Quantity <input value= '.$quantity.'></p>
                        <p class="btn-area">
                            <i class="bx bxs-trash"></i>
                            <span class="btn2">Remove</span>
                        </p>
                    </div>
                </div>';

                $total=$total+$subtotal;
        }
        $total2=$total+$shipping;
    } else {
        echo "<div class='box'>
                    
                        <h3>No result found</h3>
                </div>";
    }
} else {
    echo "Query failed: " . $conn->error; // Error message if query fails
}

echo '</div>   
            <div class="right-bar">
                <p><span>Subtotal</span><span>Rs.'.$total.'</span></p>
                <hr>
                
                <p><span>Shipping</span><span>Rs.'.$shipping.'</span></p>
                <hr>
                <p><span>Total</span><span>Rs.'.$total2.'</span></p><br>
                <a href="carddetails.php"><input type="button" class="submit" value="Checkout"></a>
                
            </div>';

$conn->close(); // Close the database connection
?>
            
        </div>
    </div>
    <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe> 
</body>
</html>

