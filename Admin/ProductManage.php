<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width,initial-scale=1.0">
    <title>Product Manage</title>

    <link
        href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100;200;300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">

    <link rel="stylesheet" href="indexstyle.css">
    <link rel="stylesheet" href="adminstyle.css">
</head>

<body>
    <div class="grid-container">

        <header class="header">
            <div class="menu-icon" onclick="openSidebar()">
                <span class="material-icons-outlined">menu</span>
            </div>
            <div class="header-left">
                <span class="material-icons-outlined">search</span>
            </div>
            <div class="header-right">
                <span class="material-icons-outlined">notifications</span>
                <span class="material-icons-outlined">email</span>
                <span class="material-icons-outlined">account_circle</span>
            </div>
        </header>

        <aside id="sidebar">
            <div class="sidebar-title">
                <div class="sidebar-brand">
                    <span class="material-icons-outlined">shopping_cart</span> STORE
                </div>
                <span class="material-icons-outlined" onclick="closeSidebar()">close</span>
            </div>

            <ul class="sidebar-list">
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">dashboard</span> Dashboard
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">inventory_2</span> Products
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">category</span> Categories
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">groups</span> Customers
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">fact_check</span> Inventory
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">poll</span> Reports
                    </a>
                </li>
                <li class="sidebar-list-item">
                    <a href="#" target="_blank">
                        <span class="material-icons-outlined">settings</span> Settings
                    </a>
                </li>
            </ul>
        </aside>
        

        <main class="main-container">

            <div class="wrapper">
                <h1>Categories</h1>
                <div class="cart">
                    <div class="shop">


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
            $img=$row["image"];
            $name=$row["categoryname"];
            $quantity=$row["quantity"];

            echo '<div class="box">
                            <img src='."hi".' alt="">
                            <div class="content">
                                <h3>'.$row["categoryname"]. '</h3>
                                <h4>'.$row["quantity"] .' Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span> 
                                </p>
                            </div>
                        </div>';
        }
        
    } else {
    echo "0 results";
}
?>
        

                        <!-- <div class="box">
                            <img src="$img" alt="">
                            <div class="content">
                                <h3></h3>
                                <h4>Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span> 
                                </p>
                            </div>
                        </div>



                         <div class="box">
                            <img src="$img" alt="">
                            <div class="content">
                                <h3><?php echo "$name"?></h3>
                                <h4><?php echo "$quantity "?>Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div> --> -->

                       <!-- <div class="box">
                            <img src="/Images/cat3.jpg" alt="">
                            <div class="content">
                                <h3>Wire Cide</h3>
                                <h4>14 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div>

                        <div class="box">
                            <img src="/Images/cat4.jpg" alt="">
                            <div class="content">
                                <h3>Heaters</h3>
                                <h4>10 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div>

                        <div class="box">
                            <img src="/Images/cat5.jpg" alt="">
                            <div class="content">
                                <h3>Decoration Bulbs</h3>
                                <h4>8 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div>

                        <div class="box">
                            <img src="/Images/cat6.jpg" alt="">
                            <div class="content">
                                <h3>Plug Top</h3>
                                <h4>3 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div>

                        <div class="box">
                            <img src="/Images/cat7.jpg" alt="">
                            <div class="content">
                                <h3>Sunk Box</h3>
                                <h4>5 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div>

                        <div class="box">
                            <img src="/Images/cat8.jpg" alt="">
                            <div class="content">
                                <h3>Junction Box</h3>
                                <h4>3 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div>

                        <div class="box">
                            <img src="/Images/cat9.jpg" alt="">
                            <div class="content">
                                <h3>Distribution Box</h3>
                                <h4>10 Items</h4>
                                <p class="btn-area">
                                    <span class="btn1">Edit</span>
                                </p>
                                <p class="btn-area">
                                    <span class="btn2">More</span>
                                </p>
                            </div>
                        </div> -->
                    </div>

                </div>
            </div>

        </main>

    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/3.35.5/apexcharts.min.js"></script>
    <script src="script.js"></script>
</body>

</html>


