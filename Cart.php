<?php
session_start();
include 'Navibar.php';

//var_dump($_POST);

 $un=$_SESSION['email'];


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
    <style type="text/css">
        .wrapper#blur.active {
    filter: blur(8px);
    pointer-events: none;
    user-select: none;
}
#remove{
    align-items: center;
    background-color: white;
    width: 450px;
    height: 300px;
    display: flex;
    justify-content: center;
    border: 0.2rem solid green;
    position: fixed;
    top: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
    visibility: hidden;
    opacity: 0;
    transition: 0.5s;
}

#remove.active{
    visibility: visible;
    opacity: 1;
    transition: 0.5s;
}


#rmvbtn{
    background-color: #FF5F1F;
    bottom: 40px;
    margin-bottom: 20px;
    width: 200px;
    height: 40px;
    border-radius: 50px;
    border: none;

}

#cancel{
    bottom: 40px;
    margin-bottom: 20px;
    width: 200px;
    height: 40px;
    border-radius: 50px;
    border: none;
}


  </style>

</head>

<body class="cart-body">

<br><br>
    <div class="wrapper" id="blur">
        <h1>shopping Cart</h1>
        <div class="cart">
            <div class="shop">
                
<?php
 $_SESSION['email']=$un;

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

$total2=0;
$total=0;
$shipping=200;
$_SESSION['shipping']=$shipping;

if ($result) { // Check if the query was successful
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $p_id=$row["Id"];
            $sub_cat = $row["sub_cat"];
            $quantity = $row["quantity"];
            $price = $row["cashPrice"];
            $img = $row["image"];
            $discount = $row["Discount"];

            $subtotal=$price*$quantity;
            

            echo '<div class="center-container">
        <div class="box">
            <input type="checkbox" name="item1_quantity[]" value="1" class="checkbox">
            <img src="data:image;base64,'. base64_encode($img) .'" alt="">
            <div class="content">
                <h3>'.$sub_cat.'</h3>
                <h4>Price: Rs. '.$subtotal.'</h4>
                <p class="unit">Quantity: '.$quantity.'</p>
                <p class="btn-area">
                    <i class="bx bxs-trash"></i>
                    <!-- Pass p_id to toggle() -->
                    <a href="#" onclick="toggle('.$p_id.')">Remove</a>
                </p>
            </div>
        </div>
    </div>';


                $total=$total+$subtotal;
        }
        $total2=$total+$shipping;
        $_SESSION['total']=$total2;
    } else {
        echo "<div class='box'>
                        <h3>No result found</h3>
            </div>";
    }
    $_SESSION['subtotal']=$total;
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
                <a href="carddetails.php"><input type="button" class="submit" value="Checkout" ></a>
                
            </div>';

$conn->close(); // Close the database connection
?>


            
        </div>
        <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe> 
    </div>
    
   <script type="text/javascript">
    function toggle(p_id) {
        var blur = document.getElementById('blur');
        blur.classList.toggle('active');
        
        var remove = document.getElementById('remove');
        remove.classList.toggle('active');

        document.getElementById('item_id').value = p_id;
    }


</script> 

<div class="rmv-product" id="remove">
    <form action="removecartitems.php" method="post" id="form">
        <h3>Remove product</h3>
        <h5>Remove item from cart?</h5>
        <!-- Hidden input field to store the item_id -->
        <input type='hidden' name='item_id' id="item_id">
        <br><br> 
        <a href="removecartitems.php?id=<?php echo $p_id; ?>">
            <button type="button" id="rmvbtn" class="btn" name="remove">Remove</button>
        </a></form><br>

            <button value="cancel" id="cancel" class="btn" name="cancel" onclick="toggle()">Cancel</button>      
</div>

</body>
</html>