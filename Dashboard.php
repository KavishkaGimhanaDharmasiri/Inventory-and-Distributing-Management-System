<?php
session_start();
$un=$_SESSION['email'];
// var_dump($_SESSION);
// var_dump($_SESSION);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lotus</title>
    <link rel="stylesheet" href="css/styledash.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" href="image/logo.png">
    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g==" crossorigin="anonymous" referrerpolicy="no-referrer" /> -->
   
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/boxicons@latest/css/boxicons.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
</head>
<body>
    <?php

include 'Navibar.php';

include 'slidebar.php';
?>

<!--Products-->

    <div class="products" id="Products">
        <div class="heading">
            <div class="product-text">
            <h1>Brows for More Products</h1>
            </div>
        </div>

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

    $sql = "SELECT * FROM categories";

    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
    
         while ($row = $result->fetch_assoc()) {
            $catnum=$row["catid"];
            $name=$row["categoryname"];
            $quantity=$row["quantity"];
            $img=$row["image"];

            echo '<div class="box">
                   
                            <div class="image">
                             <img src="data:image;base64,'.base64_encode($img).'" alt="">
                            </div>
                                
                            <h2>'.$name.'</h2>
                            <h3>'.$quantity.' Products</h3>

                            <div class="home-text">
                                <a href="Products.php?categoryid='.$catnum.'" class="btn">Shop Now<i class="bx bx-right-arrow-alt"></i></a>
                            </div>

                    </div> ';
        }
        
    } else {
    echo "0 results";
}
?>
        </div>
    </div>


    <!--About-->
    <br><br><br><br><br><br><br><br>
    <div class="about" id="About">
        <img src="Images/Decoration/electrician.jpg" alt="">
        <div class="about-text">
            <h1>About us</h1>
            <br>
            <p>

                Welcome to Lotus, your premier destination for all things electrical. At Lotus, we pride ourselves on
                offering a
                comprehensive selection of high-quality wire cords, bulbs,
                antennas, heaters, and more. With a commitment to excellence and customer satisfaction, we strive to
                provide top-notch
                products and exceptional service to meet all your electrical needs.</p>

            <p>Established with a vision to revolutionize the electrical supply industry, Lotus began as a humble
                endeavor driven
                by passion and expertise. Over the years, we have grown into a trusted name in the market, serving both
                residential and
                commercial clients with our extensive range of products.</p>

        </div>
    </div>

   <!--Review-->

<br><br><br><br>
     <div class="review" id="Review">
        <h1>Why Choose Us</h1>
        <div class="customer-container">




        <?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website';

$conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

   $sql = "
    SELECT 
        u.first_name, 
        f.feedbacks 
    FROM 
        user_details u
    INNER JOIN 
        feedback f ON u.userid = f.cus_id
";

$result = $conn->query($sql);

// Check if there are results and output them
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $name=$row['first_name'];
        $feedback=$row['feedbacks'];


            echo '<div class="box">
                <i class="bx bxs-quote-alt-left"></i>
                <div class="stars">
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star"></i>
                    <i class="bx bxs-star-half"></i>
                </div>
                <p>'.$feedback.'</p>

            </div>';
        }
        
    } else {
    echo "0 results";
}
?>



        
            

            <!-- <div class="box">
                <i class='bx bxs-quote-alt-left'></i>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star-half'></i>
                </div>
                <p>Quality Assurance: We source our products from reputable manufacturers, ensuring each item meets
                    stringent quality standards for performance and reliability.</p>

                <div class="review-profile">
                    <img src="Images/Customer_Img/cus2.jpg" alt="">
                    <h3>Roy</h3>
                </div>
            </div>

            <div class="box">
                <i class='bx bxs-quote-alt-left'></i>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star-half'></i>
                </div>
                <p>Expert Guidance: Need assistance in choosing the right product? Our knowledgeable team is here to
                    provide expert advice and guidance to help you make informed decisions.</p>

                <div class="review-profile">
                    <img src="Images/Customer_Img/cus3.jpg" alt="">
                    <h3>Jhone</h3>
                </div>
            </div>

            <div class="box">
                <i class='bx bxs-quote-alt-left'></i>
                <div class="stars">
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star'></i>
                    <i class='bx bxs-star-half'></i>
                </div>
                <p>Customer Satisfaction: Your satisfaction is our top priority. We go above and beyond to ensure a
                    seamless shopping experience, from browsing to checkout and beyond.</p>

                <div class="review-profile">
                    <img src="Images/Customer_Img/cus4.jpg" alt="">
                    <h3>Siri</h3>
                </div>
            </div> -->
        </div>
    </div>


    <!--Services-->

    <div class="services" id="Servises">
        <h1><span>our services</span></h1>

        <div class="services_cards">
            <div class="services_box">
                <i class="fa-solid fa-truck-fast"></i>
                <h3>Fast Delivery</h3>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                </p>
            </div>

            <div class="services_box">
                <i class="fa-solid fa-rotate-left"></i>
                <h3>10 Days Replacement</h3>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                </p>
            </div>

            <div class="services_box">
                <i class="fa-solid fa-headset"></i>
                <h3>24 x 7 Support</h3>
                <p>
                    Lorem ipsum dolor sit, amet consectetur adipisicing elit.
                </p>
            </div>
        </div>

    </div>


    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <!-- Initialize Swiper -->

    <script src="js/main.js">

    </script>
    <?php

include 'Footer.php';
?>
</body>
</html>