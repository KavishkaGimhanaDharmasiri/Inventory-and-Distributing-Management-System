<?php
session_start();
include("db_connection.php");
require('side_nav.php');

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
        body {
            margin: 0;
            padding: 0;
            height: 100vh;
            position: relative;
        }

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
            border: 1px solid #45a049;
           
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
            text-align: center;
    

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
            margin-bottom: 400px;
            
        }

        .profile-panel {
            display: none;
            position: fixed;
            top: 45%;
            left: 85%;
            transform: translate(-50%, -50%);
            background-color: white;
            padding: 10px;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
            width: 200px;
            height: 320px;
            border: 1px solid #45a049;
           
        }


        .our-team {
  margin-bottom: 5px;
  background-color: transparent;
  text-align: center;
  overflow: hidden;
  position: relative;
  border-radius: 15px;
}

.our-team .picture {
  display: inline-block;
  height: 100px;
  width: 100px;
  margin-bottom: 10px;
  z-index: 1;
  position: relative;
}

.our-team .picture::before {
  content: "";
  width: 100%;
  height: 0;
  border-radius: 50%;
  background-color: #4caf50;
  position: absolute;
  bottom: 135%;
  right: 0;
  left: 0;
  opacity: 0.9;
  transform: scale(3);
  transition: all 0.3s linear 0s;
}

.our-team:hover .picture::before {
  height: 100%;
}

.our-team .picture::after {
  content: "";
  width: 100%;
  height: 100%;
  border-radius: 50%;
  background-color: #4caf50;
  position: absolute;
  top: 0;
  left: 0;
  z-index: -1;
}

.our-team .picture img {
  width: 100%;
  height: auto;
  border-radius: 50%;
  transform: scale(1);
  transition: all 0.9s ease 0s;
}

.our-team:hover .picture img {
  box-shadow: 0 0 0 14px #4caf50;
  transform: scale(0.7);
}

.our-team .title {
  display: block;
  font-size: 15px;
  color: #4e5052;
  text-transform: capitalize;
}



    </style>
    </head>
<body>
    
    <div class="options-container">
    <a href="new_new_order.php" class="option"><div >Add Order</div></a>
    <a href="view_order.php" class="option"><div>View Order</div></a>
    <a href="add_customer.php" class="option"><div >Add Customer</div></a>
    <a href="report.php" class="option"><div >Generate Report</div></a>
    <a href="summery.php" class="option"><div >Summary</div></a>
    </div>
    <span onclick="toggleProfilePanel()" style="background-color: transparent; cursor: pointer; font-size:32px; position: absolute;top: 8px;right: 16px;"><b>&#9812;</span>

    <div id="profilePanel" class="profile-panel">

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="our-team">
        <div class="picture">
          <img class="img-fluid" style="height: 100px; width: 100px;" src="https://picsum.photos/id/77/1631/1102">
        </div>
        <div class="team-content">
          <?php  echo '<h3 class="name">'.$_SESSION["user_log_fname"]." ".$_SESSION["user_log_lname"].'</h3>
          <h4 class="title">'. $_SESSION["state"].'</h4><h4 class="title">'.$_SESSION["user_log_email"].'</h4>'; ?>
<button onclick="changePassword()" class="changePass">Change Password</button>
        
        <button type="submit"  onclick="logout()" name="logout" id="logout" style="background: transparent;color: green;" >Logout</button>
        </div>
      </div>
       
    </div>

   
       
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