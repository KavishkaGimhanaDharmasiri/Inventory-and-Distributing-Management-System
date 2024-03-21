<?php
session_start();
include("db_connection.php");

$user_idn=$_SESSION["user_id"];

$query = "SELECT * FROM users WHERE user_id = '$user_idn'";
    $result = mysqli_query($connection, $query);

    if ($result) {
        // Check if a matching record is found
        if (mysqli_num_rows($result) == 1) {
            // Fetch user data
            $row = mysqli_fetch_assoc($result);

            // Set session variables
            $_SESSION["user_log_fname"] = $row["firstName"];
            $_SESSION["user_log_lname"] = $row["LastName"];
            $_SESSION["user_log_email"] = $row["email"];
}
}
unset($_SESSION['order_details']);
unset($_SESSION['totalAmount']);
unset($_SESSION['paymentAmount']);
unset($_SESSION['balance']);
unset($_SESSION['selected_payment_method']);
unset($_SESSION['selected_store']);

// End the session
session_write_close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Options</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .options-container {
            background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            width: 100%;
            max-width: 600px;
            display: flex;
            flex-wrap: wrap;
            justify-content: space-around;
            border: 2px solid #4caf50;
           
        }

        .option {
            width: 45%;
            margin-bottom: 20px;
            background-color: #4caf50;
            color: #fff;
            text-align: center;
           padding: 15px;
            border-radius: 15px;
            cursor: pointer;
            padding-right:5px;
            
    
        }
        a{
            text-decoration: none;
            color:white;
    

        }

        .option:hover {
            background-color: #45a049;
        }

        @media (max-width: 600px) {
            .option {
                width: 100%;
            }
        }

        .profile-icon {
            background-image: url('profile.png');
            position: absolute;
            top: 40px;
            right: 40px;
            height: 20px;
            width: 20px;
            margin-bottom: 500px;
            
        }
        .profile-icon {
            
        }

        .profile-panel {
            display: none;
            position: fixed;
            top: 32%;
            left: 67%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 190px;
                border:1px solid #45a049;
        }

    </style>
    </head>
<body>
    
    <div class="options-container">
    <div class="option" ><a href="new_order.php " class="option">Add Order</a></div>
    <div class="option"><a href="view_order.php">View Order</a></div>
    <div class="option"><a href="add_customer.php">Add Customer</a></div>
    <div class="option"><a href="report.php">Generate Report</a></div>
    <div class="option"><a href="summery.php">Summary</a></div>
</div>
<!--<div class="back-button" onclick="history.back()"><h4>Back</h4></div>-->
    <div class="profile-icon" onclick="toggleProfilePanel()" ><button style="background-color: transparent;" ><b><b><font size="15px" color="black">₪</font></b><b></button></div>

    <div id="profilePanel" class="profile-panel">
        <h4 style="text-align: center;">User Profile</h4>
        <div id="profileDataContainer"></div>
        <?php echo '<h5> Logging :'. $_SESSION["state"].'<br><h5> Name : '. $_SESSION["user_log_fname"]." ".$_SESSION["user_log_lname"]. '<br><br>Email : '.$_SESSION["user_log_email"].'</h5>'; ?>
        <button onclick="changePassword()" class="changePass">Change Password</button>
        <br><br>
        
        <button type="submit"  onclick="logout()" name="logout" id="logout" style="background: transparent;border: 2px solid red;color: red;" >Logout</button>
    </div>
    <script>
     
        function toggleProfilePanel() {
        var profilePanel = document.getElementById('profilePanel');
        profilePanel.style.display = (profilePanel.style.display === 'block') ? 'none' : 'block';
        //fetchUserData()
    }

    function changePassword() {
        // Implement the logic to change the password here
        window.location.href = 'chng_pass.php';
    }

    function logout() {
    // AJAX request to a PHP script that handles session logout
    var xmlhttp = new XMLHttpRequest();
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            // Redirect to login.php after successful logout
            window.location.href = "index.php";
        }
    };
    xmlhttp.open("GET", "index.php", true);
    xmlhttp.send();
}
</script>
</body>
</html>