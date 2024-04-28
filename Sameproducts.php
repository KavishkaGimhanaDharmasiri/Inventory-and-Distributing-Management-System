<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Product Animation Example</title>
    <style>

        .container {
            width: 100%;
            height: 350px;
            overflow: hidden;
            position: relative;
            display: flex;
            justify-content: center; /* Center the products horizontally */
        }

        .products-container {
            display: flex;
            animation: moveLeft 50s linear infinite;
        }

        .box {
             padding: 20px;
             margin: 10px;
              box-shadow: 1px 2px 11px 4px rgb(14 55 54/15%);
              border-radius: 1.5rem;
               width: 250px;
               height: 250px;
               text-align: center;
             border-radius: 100%;
              border: 7px solid green;
           transition: width 2s, height 2s, transform 2s;
           
        }

    .box:hover {
        width: 280px;
        height: 280px;
  
}

        .box img {
            width: 50%;
            border-radius: 100%;
        }

        .box h3{
            align:center;
             font-size: 1.2rem;
              color:#FF5722;
            
        }

        .box h4{
           color: green;
            align:center;
        }

        a {
            text-decoration: none;
            }

        @keyframes moveLeft {
            to { transform: translateX(100%); }
            from { transform: translateX(-100%); }
        }

        h1 {
              padding-left: 40px;
              padding-bottom: 40px;
              font-size: 30px;
              background: -webkit-linear-gradient(#FF8A65, #E64A19);
              -webkit-background-clip: text;
              -webkit-text-fill-color: transparent;
            }
    </style>
</head>
<body>
    <h1>Relevent Products</h1>
    <div class="container">

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

            $sql = "SELECT * FROM product WHERE main_cat='001'";
            $result = $conn->query($sql);

            if ($result) { // Check if the query was successful
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $product_id = $row["product_id"];
                        
                        $sub_cat = $row["sub_cat"];
                       
                        $price = $row["cashPrice"];
                       
                        $img = $row["image"];
                        

                        echo '<div class="box">
                                    <a href="Productdetails.php?categoryid='.$product_id.'">
                        
                                    <div class="image">
                                        <img src="data:image;base64,' . base64_encode($img) . '" alt="">
                                    </div>
                                        <h3>' . $sub_cat . '</h3>
                                        <h4 class="price">Rs. ' . $price . '</h4>
                                    
                                    </a>
                              </div>';
                    }
                } else {
                    echo "0 results";
                }
            } else {
                echo "Query failed: " . $conn->error; // Error message if query fails
            }

            $conn->close(); // Close the database connection
            ?>
        </div>
         <path id="curve" d="M73.2,148.6c4-6.1,65.5-96.8,178.6-95.6c111.3,1.2,170.8,90.3,175.1,97" />
    <text width="500">
      <textPath xlink:href="#curve">
        Dangerous Curves Ahead
      </textPath>
    </text>
    </div>
</body>
</html>
