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

include 'Header.php';
?>


    <div class="card-wrapper">
        <div class="card">
            <div class="product-img">
                <div class="img-display">
                    <div class="img-showcase">
                        <img src="Images/heaters.jpg" alt="">
                        <img src="Images/antenna.jpg" alt="">
                        <img src="Images/bulb.jpg" alt="">
                        <img src="Images/cables2.jpg" alt="">
                    </div>
                </div>
                <div class="img-select">
                    <div class="img-item">
                        <a href="#" data-id="1">
                            <img src="Images/heaters.jpg" alt="">
                        </a>
                    </div>

                    <div class="img-item">
                        <a href="#" data-id="2">
                            <img src="Images/antenna.jpg" alt="">
                        </a>
                    </div>

                    <div class="img-item">
                        <a href="#" data-id="3">
                            <img src="Images/cables2.jpg" alt="">
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="product-content">
            <h2 class="product-title">Antenna</h2>
            <a href="#" class="product-link">Visit site</a>

            <div class="product-rating">
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star'></i>
                <i class='bx bxs-star-half'></i>
                <span>4.7(21)</span>
            </div>

            <div class="product-price">
                <p class="last-price">Old price: <span>Rs.1200</span></p>
                <p class="new-price">New price: <span>Rs.1100</span></p>
            </div>

            <div class="product-detail">
                <h2>About this item: </h2>
                <p>Antenna contain with 10 Elements Yagi type antenna 15m RG6 coaxial cable U-bolt SMA to F female
                    adapter SMA Male Connector adapter for portable dongles and routers.</p>
                <ul>
                    <li>Antenna Type: Outdoor</li>
                    <li>Model: Yagi</li>
                    <li>Brand: No Brand</li>
                </ul>
            </div>

             <!-- <a href="Cart" class="btn">Shop Now<i class='bx bx-right-arrow-alt'></i></a> -->

            <div class="purchase-info">
                <input type="number" min="0" value="1"><br>
                <a href="SignIn.php" class=" ">
                <button type="button" class="btn">
                    Add to Cart <i class='bx bx-cart-alt'></i>
                </button></a>
                <!-- <button type="button" class="btn">Compare</button> -->
            </div>
        </div>
    </div>
    <script src="js/productProductdetails.js"></script>
    <iframe src="Footer.php" frameborder="0" width="100%" height="250"></iframe>
</body>