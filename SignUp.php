<?php

session_start();
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
            top: 50%;
            bottom: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
         /*overflow: hidden;*/
            width: 600px;
            height:900px;
            max-width: 100%;
            margin-top: 100px;
           position: relative;
            
        }

        #form {
            background-color: rgba(256, 256, 256, 0.2);
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
            height: 120px;
            width: 120px;
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
            <table id="table">
                <tr>
                    <td><img src="./Images/Decoration/lotus.png" alt="logo" id="img"></td>
                    <td>
                        <h1 style="color: green;">Lotus</h1>
                    </td>
                </tr>
            </table>

            <h2 style="color: black;" align="center">Sign Up</h1>

                <span id="fname_msg" style="color: red; font-size: 12px;"> </span>
                <input type="text" placeholder="First Name" id="fname" name="fname">

                <span id="lname_msg" style="color: red; font-size: 12px;"> </span>
                <input type="text" placeholder="Last Name" id="lname" name="lname">

                <span id = "dob_msg" style="color:red"> </span>
                <input type="date" placeholder="Date of Birth" id="dob" name="dob">

                <span id = "address_msg" style="color:red"> </span>
                <input type="text" placeholder="Address" id="address" name="address">

                <span id = "number_msg" style="color:red"> </span>
                <input type="tel" placeholder="Contact NUmber" id="tnumber" name="tnumber">

                <span id = "email_msg" style="color:red"> </span>
                <input type="text" placeholder="Email" id="email" name="email">

                <span id = "pwd1_msg" style="color:red"> </span>
                <input type="password" placeholder="Password" id="password1" name="password" >
                <label for="showPassword">
                <input type="checkbox" id="showPassword"> Show Password
                </label>

                <span id = "pwd2_msg" style="color:red"> </span>
                <input type="password" placeholder="Confirm Password" id="password2" ><br>

                <button type="submit" value="Sign Up" id="button" name="signUp" onclick="check_form()">Sign Up</button><br>
                
                
                <table id="table">
                    <tr>
                        <td>Already have an account ? </td>
                        <td> <a href="SignIn.php">Sign In</a></td>
                    </tr>
                </table>

        </form>
        <br>

  
<script>
        document.getElementById('showPassword').addEventListener('change', function() {
            var passwordInput = document.getElementById('password1');
            if (this.checked) {
                passwordInput.type = 'text';
            } else {
                passwordInput.type = 'password';
            }
        });
   

function check_form() {
    document.getElementById("form").addEventListener("submit", function(event) {
        var fname = document.getElementById("fname").value;
        var lname = document.getElementById("lname").value;
        var dob = document.getElementById("dob").value;
        var address = document.getElementById("address").value;
        var tnumber = document.getElementById("tnumber").value;
        var email = document.getElementById("email").value;
        var password1 = document.getElementById("password1").value;
        var password2 = document.getElementById("password2").value;

        var letters = /^[A-Za-z]+$/;
        var pattern = /^([0-9]{2})\/([0-9]{2})\/([0-9]{4})$/;
        var phoneno = /^\d{10}$/;
        var mailFormat = /\S+@\S+\.\S+/;

        if (fname == null || fname == "") {
            text = '**Please enter your Name**';
            document.getElementById("fname_msg").innerHTML = text;
            event.preventDefault();
        } else if (!fname.match(letters)) {
            text = '**Enter Characters Only**';
            document.getElementById("fname_msg").innerHTML = text;
            event.preventDefault();
        } else if (lname == null || lname == "") {
            text = '**Please enter your Name**';
            document.getElementById("lname_msg").innerHTML = text;
            event.preventDefault();
        } else if (!lname.match(letters)) {
            text = '**Enter Characters Only**';
            document.getElementById("lname_msg").innerHTML = text;
            event.preventDefault();
        // } else if (!pattern.test(dob)) {
        //     text = '**Invalid Date of Birth (DD-MM-YYYY)**';
        //     document.getElementById("dob_msg").innerHTML = text;
        //     event.preventDefault();
         } else if (address == null || address.trim() === "") {
            text = '**Enter Your Address**';
            document.getElementById("address_msg").innerHTML = text;
            event.preventDefault();
        } else if (tnumber == null || tnumber.trim() === "") {
            text = '**Enter Your Number**';
            document.getElementById("number_msg").innerHTML = text;
            event.preventDefault();
        } else if (!tnumber.match(phoneno)) {
            text = '**Enter Numbers Only**';
            document.getElementById("number_msg").innerHTML = text;
            event.preventDefault();
        } else if (email == null || email.trim() === "") {
            text = '**Enter Your Email**';
            document.getElementById("email_msg").innerHTML = text;
            event.preventDefault();
        } else if (!email.match(mailFormat)) {
            text = '**Invalid Email Format**';
            document.getElementById("email_msg").innerHTML = text;
            event.preventDefault();
        } else if (password1 == null || password1.trim() === "") {
            text = '**Enter Your Password**';
            document.getElementById("pwd1_msg").innerHTML = text;
            event.preventDefault();
        } else if (password2 == null || password2.trim() === "") {
            text = '**Enter Your Confirm Password**';
            document.getElementById("pwd2_msg").innerHTML = text;
            event.preventDefault();
        } else if (password1 !== password2) {
            text = '**Passwords do not match**';
            document.getElementById("pwd1_msg").innerHTML = text;
            event.preventDefault();
        }

    });
}

</script>
    </div>
</body>
</html>
<?php

if(isset($_POST['signUp']))
{
   
    $fname =$_POST['fname'];
    $lname =$_POST['lname'];
    $dob =$_POST['dob'];
    $address = $_POST['address'];
    $tnumber = $_POST['tnumber'];
    $email = $_POST['email'];
    $password = $_POST['password'];
     $_SESSION['usrname'] = $email;
     $_SESSION['email'] = $email;
            
            //$_SESSION['user_id']=$id;
            $_SESSION['f_name']=$fname;

    //connect to DB
    $host='localhost';
    $username='root';
    $password="";
    $database="retail_website";

    $link=mysqli_connect($host,$username,$password,$database);

        if(!$link){
            die('could connect'.mysqli_error($link));
        }
        //echo 'connected successfully';

    $stmt = $link->prepare("CALL create_user(?, ?, ?, ?, ?, ?, ?, @status)");

      $stmt->bind_param("ssssiss", $fname, $lname, $dob, $address, $tnumber, $email,  $password);

    $stmt->execute();

    $stmt->close();
    $result = $link->query("SELECT @status AS status");
    $row = $result->fetch_assoc();
    $status = $row['status'];

    switch ($status) {
        case 0:
            // echo "Student inserted successfully."; 
        echo '<script>window.alert("User inserted successfully.");
        window.location.href="SignIn.php";
        exit();</script>'; 
        
            break;
        case 1:
            echo'<script>window.alert("Error occurred while inserting student.");
            event.preventDefault();
            return false;
            </script>';
            break;
        case 2:
            echo'<script>window.alert("Email already exists in the database.");
            event.preventDefault();
            return false;
            </script>';
            break;
        default:
            echo'<script>window.alert("Unknown status returned.")
            event.preventDefault();
            return false;
            </script>';
            break;
    }

    $link->close();
}
?>

