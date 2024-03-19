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
<?php

include 'Header.php';
?>
    <div class="wrapper">
        <h1>shopping Cart</h1>
        <div class="cart">
            <div class="shop">
                <div class="box">
                    <img src="Images/bulb.jpg" alt="">
                    <div class="content">
                        <h3>Bulbs</h3>
                        <h4>Price: Rs.150</h4>
                        <p class="unit">Quantity<input value="2"></p>
                        <p class="btn-area">
                            <i class='bx bxs-trash'></i>
                            <span class="btn2">Remove</span>
                        </p>
                    </div>
                </div>

                <div class="box">
                    <img src="Images/Cables.jpg" alt="">
                    <div class="content">
                        <h3>Cable</h3>
                        <h4>Price: Rs.150</h4>
                        <p class="unit">Quantity<input value="2"></p>
                        <p class="btn-area">
                            <i class='bx bxs-trash'></i>
                            <span class="btn2">Remove</span>
                        </p>
                    </div>
                </div>

                <div class="box">
                    <img src="Images/heaters2.jpg" alt="">
                    <div class="content">
                        <h3>Heater</h3>
                        <h4>Price: Rs.150</h4>
                        <p class="unit">Quantity<input value="2"></p>
                        <p class="btn-area">
                            <i class='bx bxs-trash'></i>
                            <span class="btn2">Remove</span>
                        </p>
                    </div>
                </div>

                <div class="box">
                    <img src="Images/cables2.jpg" alt="">
                    <div class="content">
                        <h3>Cable</h3>
                        <h4>Price: Rs.150</h4>
                        <p class="unit">Quantity<input value="2"></p>
                        <p class="btn-area">
                            <i class='bx bxs-trash'></i>
                            <span class="btn2">Remove</span>
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="right-bar">
                <p><span>Subtotal</span><span>Rs.120</span></p>
                <hr>
                <p><span>Tax(5%)</span><span>Rs.6</span></p>
                <hr>
                <p><span>Shipping</span><span>Rs.15</span></p>
                <hr>
                <p><span>Total</span><span>Rs.141</span></p>
                <input type="submit" class="submit" value="Checkout"><a href="#"></a></submssss>
            </div>
        </div>
    </div>
    <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe>
</body>