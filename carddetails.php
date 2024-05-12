<?php
session_start();
$total=$_SESSION['total'];
$subtotal=$_SESSION['subtotal'];
$shipping=$_SESSION['shipping'];
$name=$_SESSION['f_name'];
$email=$_SESSION['email'];
?>
<!DOCTYPE html>
<html>
<head>
    <title>W3.CSS</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <style><style>
        #div1 {
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
                0 10px 10px rgba(0, 0, 0, 0.22);
            
            transform: translate(-50%, -50%);
            width: 1200px;
            height:900px;
            max-width: 100%;
            margin-top: 100px;
        }

       

        #table-logo{
             display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 50px;
            height: 100%;
            text-align: center;
            padding-bottom: 50px; 
        }

        #form {
             align-items: center;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
                0 10px 10px rgba(0, 0, 0, 0.22);
            top: 10%;
            bottom: 50%;
            left: 35%;
            transform: translate(-50%, -20%);
            width: 900px;
            height:900px;
            max-width: 100%;
            margin-top: 100px;
           position: relative;
          
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
            padding-bottom: 50px; 
        }

        #text {
            background-color: #eee;
            border: none;
            border-radius: 10px;
            padding: 12px 15px;
            margin: 8px 0;
            width: 400px;
        }

        #button {
            border-radius: 20px;
            border: 1px solid #27ae60;
            background-color: #27ae60;
            color: black;
            font-size: 15px;
            font-weight: bold;
            padding: 10px 50px;
            letter-spacing: 1px;
            width: 150px;
        } 

        #img {
            height: 120px;
            width: 120px;
        }

        .tab2 td{
            padding-top: 10px;
            padding-right: 20px;
            padding-left: 20px;
        }

        .tab3 td  {
            padding-left: 40px;
            padding-right: 40px;
        }

        .tab3 .text {
            background-color: #eee;
            border: none;
            border-radius: 10px;
            padding: 12px 15px;
            margin: 8px 0;
            width: 160px;;
        }

        select{
            border-radius: 20px;
           border: none;
            height: 30px;
            width: 350px;
        }

        label,h6{
            color:#5C5D5D;
        }

        img{
            height: 40px;
            width: 150px;
        }

        .right-bar{
            position: absolute; top: 170px; right: 150px;
  
    padding: 20px;
    height: 400px;
    width: 30pc;
    border-radius: 5px;
    background: #fff;
    box-shadow: rgb(100,100,111,0.2)0 7px 29px 0;
}

.right-bar hr{
    margin-bottom: 25px;
}

.right-bar p{
    display: flex;
    justify-content: space-between;
    margin-bottom: 30px;
    font-size: 20px;
}

    .submit{
        background: #009933;
    border-radius: 30px;
    font-weight: 600;
    border: none;
    padding: 10px;
    width: 200px;
    margin-left:130px;
    margin-right: auto;
    }
    .submit:hover{
    background:#e60000;
    transition: 0.2s ease;
}

    </style></style>
</head>
<body>
        <table id="table-logo">
                        <tr>
                            <td><img src="Images/Decoration/lotus.png" alt="logo" id="img"></td>
                            <td>
                                <h1 style="color: green;">Lotus</h1>
                            </td>
                        </tr>
                    </table>

        <div id="div1">
            
                <form action="" method="post" id="form">
                    
                    <h2 style="color: black;" align="center">Ship to</h1>

                    <table class="tab2">

                    <tr>
                        <td> <input type="text" placeholder="<?php echo $name; ?>" id="text" name="name" value=<?php echo $name; ?>></td><td> <input type="text" placeholder=<?php echo $email; ?> id="text" name="email" value=<?php echo $email; ?>></td>
                    </tr>

                    <tr>
                        <td> <input type="text" placeholder="Street address" id="text" name="Street_address"></td><td><input type="text" placeholder="Street address (optional)" id="text" name="Street_address2"></td>
                    </tr>

                    <tr>
                        <td><input type="text" placeholder="Address" id="text" name="address"></td>
                    </tr>
                    </table>

                    <table class="tab3">
                        <tr>
                            <td><input type="text" placeholder="City" class="text" name="city"></td>
                            <td> <input type="text" placeholder="Province" class="text" name="province"></td>
                            <td> <input type="text" placeholder="Zip Code" class="text" name="zip"></td>
                            </tr>
                    </table>
           
                       <br><br> <button type="submit" value="Sign Up" id="button" class="button" name="add_shipping_details">Done</button><br>
                        
                </form>
        </div>

        <div id="div2">
            <form action="" method="post" id="form">
                    
                    <h2 style="color: black;" align="center">Pay with</h1>

                    <table class="tab2">

                    <tr>
                        <td><label for="bank">Bank:</label>
                              <select name="bank" id="bank" name="bank">
                                <option value="BOC">BOC</option>
                                <option value="COM">Commercial Bank</option>
                                <option value="HNB">Hatton National Bank</option>
                                <option value="NSB">National Savings Bnak</option>
                                <option value="Peoples">Peoples Bank</option>
                                <option value="Sampath">Sampath Bnak</option>
                              </select></td>
                    <td> <h6>Payment Types: </h6> <img src="Images/Decoration/cards.png"></td>
                    </tr>

                     <tr>
                        <td><input type="text" placeholder="Card number" id="text" name="cardnumber"></td><td> <input type="text" placeholder="cvv" id="text" name="cvv"></td>
                    </tr>

                    </table>

                    <table class="tab3">
                        <tr>
                            <td><input type="text" placeholder="Expiration date" class="text" name="expD"></td>
                            <td> <input type="text" placeholder="Expiration Month" class="text" name="expM"></td>
                            </tr>
                    </table>
           
                       <br><br> <button type="submit" value="Sign Up" id="button" class="button" name="Card_details">Done</button><br>
                        
                </form>
        </div>

        <div class="right-bar">
                <p><span>Subtotal</span><span>Rs.<?php echo $subtotal?></span></p>
                <hr>
                
                <p><span>Shipping</span><span>Rs.<?php echo $shipping?></span></p>
                <hr>
                <p><span>Total</span><span>Rs.<?php echo $total?></span></p><br>
                <input type="submit" class="submit" value="Confirm and pay" name="add_card_details"></a>
                
            </div>

</body>
</html>

<?php
if(isset($_POST['add_shipping_details'])) // Check if the form is submitted
{
    // Retrieve form data
    $email = $_POST['email'];
    $street_address = $_POST['Street_address'];
    $street_address2 = $_POST['Street_address2'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $province = $_POST['province'];
    $zip = $_POST['zip'];

    echo $email,$street_address,$street_address2, $address,$city,$province,$zip;
    // Connect to the database
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'retail_website';

    $link = mysqli_connect($host, $username, $password, $database);

    // Check the connection
    if (!$link) {
        die('Could not connect: ' . mysqli_connect_error()); // Improved error message
    }
    echo 'connected';
    // Prepare and execute the stored procedure
    $stmt = $link->prepare("CALL add_shippingdetaills(?, ?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Failed to prepare statement: ' . $link->error); // Error handling
    }
    $bindResult = $stmt->bind_param("ssssssi", $email, $street_address, $street_address2, $address, $city, $province, $zip);
    if (!$bindResult) {
        die('Binding parameters failed: ' . $stmt->error); // Error handling
    }
    
    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Execution failed: ' . $stmt->error); // Error handling
    }

    $stmt->close();
}
?>

<?php

if(isset($_POST['Card_details'])) // Check if the form is submitted
{

    // Retrieve form data
    $bank = $_POST['bank'];
    $cardnumber = $_POST['cardnumber'];
    $cvv = $_POST['cvv'];
    $expM = $_POST['expM'];
    $expD = $_POST['expD'];

    // echo $bank, $email, $cardnumber, $cvv, $expD, $expM;

     $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'retail_website';

    $link = mysqli_connect($host, $username, $password, $database);

    // Check the connection
    if (!$link) {
        die('Could not connect: ' . mysqli_connect_error()); // Improved error message
    }
    
    $stmt = $link->prepare("CALL add_carddetails(?, ?, ?, ?, ?, ?)");
    if (!$stmt) {
        die('Failed to prepare statement: ' . $link->error); // Error handling
    }
    $bindResult = $stmt->bind_param("ssiiii", $email, $bank, $cardnumber, $cvv, $expD, $expM);
    if (!$bindResult) {
        die('Binding parameters failed: ' . $stmt->error); // Error handling
    }
    
    $executeResult = $stmt->execute();
    if (!$executeResult) {
        die('Execution failed: ' . $stmt->error); // Error handling
    }

    $stmt->close();
}

?>

