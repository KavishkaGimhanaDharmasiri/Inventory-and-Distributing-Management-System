
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <style>
       #div1 {
            align-items: center;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
                0 10px 10px rgba(0, 0, 0, 0.22);
            top: 50%;
            bottom: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
         /*overflow: hidden;*/
            width: 1200px;
            height:900px;
            max-width: 100%;
            margin-top: 100px;
           position: relative;
        }

        #form {
            /* background-color: rgba(256, 256, 256, 0.2); */
           
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
            width: 450px;
        } 

        #img {
            height: 120px;
            width: 120px;
        }

        .tab2 td{
            padding-top: 10px;
            padding-right: 60px;
            padding-left: 60px;
        }

        .tab3 td {
            padding-left: 50px;
            padding-right: 50px;
        
        }

        .tab3 .text{
            background-color: #eee;
            border: none;
            border-radius: 10px;
            padding: 12px 15px;
            margin: 8px 0;
            width: 150px;;
        }
        
        /* body{
            background-color: rgba(255, 255, 255, 0.5);
        } */

    </style>
</head>
<body>

<div id="div1">
    <form action="testDbconn.php"  method="post" id="form">
        <table id="table">
            <tr>
                <td><img src="./Images/lotus.png" alt="logo" id="img"></td>
                <td>
                    <h1 style="color: green;">Lotus</h1>
                </td>
            </tr>
        </table>

        <h2 style="color: black;" align="center">Register</h1>

        <table class="tab2">
            <tr>
                <td><input type="text" placeholder="Name" id="text" name="name"></td><td></td>
            </tr>

            <tr>
                <td> <input type="text" placeholder="Email" id="text" name="email"></td><td> <input type="text" placeholder="Name on Card" id="text" name="cardname"></td>
            </tr>

            <tr>
                <td> <input type="text" placeholder="Address" id="text" name="address"></td><td><input type="text" placeholder="Credit Card Number" id="text" name="cardnumber"></td>
            </tr>

            <tr>
                <td><input type="text" placeholder="City" id="text" name="city"></td><td> <input type="text" placeholder="Exp Month" id="text" name="expmonth"></td>
            </tr>
        </table>

        <table class="tab3">
            <tr>
                <td><input type="text" placeholder="State" class="text" name="state"></td>
                <td> <input type="text" placeholder="Zip Code" class="text" name="zip"></td>
                <td> <input type="text" placeholder="Exp Year" class="text" name="expyear"></td>
                <td> <input type="text" placeholder="CVV" class="text" name="cvv"></td></tr>
        </table>

           <br><br> <button type="submit" value="Sign Up" id="button" class="button" name="sub">Register</button><br>
            
    </form>
</div>

</body>
</html>;


<?php
if(isset($_POST['sub']))
{
    $id= $_POST['name'];
    $name =$_POST['name'];
    $email = $_POST['email'];
    $address = $_POST['address'];
    $cardname = $_POST['cardname'];
    $cardnumber = $_POST['cardnumber'];
    $city = $_POST['city'];
    $expmonth = $_POST['expmonth'];
    $state = $_POST['state'];
    $zip = $_POST['zip'];
    $expyear = $_POST['expyear'];
    $cvv = $_POST['cvv'];

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

    $usrer_insert="INSERT INTO users(id,name,email,address,cardnumber,cardname,city,expmonth,state,zip,expyear,cvv) VALUES ('$id','$name', '$email', '$address','$cardnumber','$cardname','$city','$expmonth','$state','$zip','$expyear','$cvv')";


        if ($link->query($usrer_insert) === TRUE) {
        echo "New record created successfully";
        } else {
        echo "Error: " . $usrer_insert . "<br>" . $link->error;
    }   
    
    mysqli_close($link);
} 
?>   