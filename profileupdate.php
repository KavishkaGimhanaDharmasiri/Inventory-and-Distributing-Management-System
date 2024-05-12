<?php
session_start();
$un=$_SESSION['email'];
include 'Navibar.php';
?>


<head>
    <title>
        Sign Up
    </title>
    
    <script src="js/jquery-3.6.0.min.js"></script>
   <!-- <script src="js/signup.js"></script>  -->
    <style>
        #div1 {
            align-items: center;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 14px 28px rgba(0, 0, 0, 0.25),
                0 10px 10px rgba(0, 0, 0, 0.22);
            top: 35%;
            bottom: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
         /*overflow: hidden;*/
            width: 600px;
            height:600px;
            max-width: 100%;
            margin-top: 100px;
           position: relative;
          
            
        }

        #form {
/*            background-color:red;*/
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            padding: 0 50px;
            height: 100%;
            text-align: center;
        }

        #fname,#lname, #dob,#tnumber,#address,#address,#email,#postalcode,#password1,#password2 {
            background-color: #eee;
            border: none;
            border-radius: 20px;
            padding: 12px 15px;
            margin: 8px 0;
            width: 100%;
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
            width: 300px;

        }

        #img {
            height: 50px;
            width: 50px;
            color: green;
        }

       

        td{
            padding-right: 30px;
        }

    </style>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL"
        crossorigin="anonymous"></script>
</head>

<body>
    <div id="div1">
        <form action="" method="post" id="form">
            
<?php

$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website'; // corrected spelling of 'inventory'

$conn = new mysqli($host, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM user_details WHERE email='$un'"; // Wrap $un in quotes

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $fname = $row["first_name"];
        $address = $row["address"];
        $tp = $row["number"];
        $email = $row["email"];
        $pwd = $row["password"];

        echo '<table id="table">
                <tr>
                    <td><img src="./Images/Decoration/manprofile.png" alt="logo" id="img"></td>
                    <td>
                        <h1 style="color: black;">HELLO ' . $fname . '</h1>
                    </td>
                </tr>
            </table>

            <h2 style="color: black;" align="center"></h2>

            <span id="email_msg" style="color:red"> </span>
            <input type="text" placeholder="' . $email . '" id="email" name="email">

            <span id="address_msg" style="color:red"> </span>
            <input type="text" placeholder="' . $address . '" id="address" name="address">

            <span id="number_msg" style="color:red"> </span>
            <input type="tel" placeholder="' . $tp . '" id="tnumber" name="tnumber">

            <span id="pwd1_msg" style="color:red"> </span>
            <input type="password" placeholder="Old password" id="password1" >

            <span id="pwd2_msg" style="color:red"> </span>
            <input type="password" placeholder="New password" id="password2" name="password"><br>

            <button type="submit" value="update" id="button" name="update">Update</button><br>

        </form>';
    }
} else {
    echo "No user found.";
}

if(isset($_POST['update'])){
    $up_email = $_POST['email'];
    $up_address = $_POST['address'];
    $up_number = $_POST['tnumber'];
    $up_pwd = $_POST['password'];

    if($up_email == ''){
        $up_email = $email;
    }
    if ($up_address == '') {
        $up_address = $address;
    }
    if($up_number == ''){
         $up_number = $tp;
    }
    if($up_pwd == ''){
        $up_pwd = $pwd;  
    }

    $sql = "UPDATE user_details SET address = '$up_address', 
                        number = '$up_number', email = '$up_email', 
                        password = '$up_pwd' 
                        WHERE email = '$un'";

    $result = $conn->query($sql);
    if (!$result) {
        die('Update failed: ' . $conn->error);
    }

    $_SESSION['email']=$up_email;


    //echo '<script>setTimeout(function(){ location.reload(); }, 2000);</script>';

    $conn->close();
}


?>



        
        <br>
    </div>
</body>
</html>

