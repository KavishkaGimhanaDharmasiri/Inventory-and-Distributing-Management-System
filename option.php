<?php
session_start();
include("db_connection.php");
require_once('den_fun.php');
require_once('seq.php');

if(!isset($_SESSION['index_visit']) || !$_SESSION['index_visit'] || !isset($_SESSION["user_id"] ) || !isset($_SESSION["state"]) ){
    
acess_denie();
    exit();

}
if(window.back()){
    
}
else{

$user_idn=$_SESSION["user_id"];

$_SESSION['option_visit']=true;
}

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
    <link rel="stylesheet" type="text/css" href="option.css">
    <link rel="stylesheet" type="text/css" href="sidebarnav.css">
    <link rel="stylesheet" type="text/css" href="seqnav.css">
       <style>
       
  </style>

    </head>
<body>
    <?php 
sequence();
?>


<a href="" onclick="logout()">logout</a>
    


   

    <div class="options-container">

    <a href="new_order.php" class="option" id="option1" style="display: none;"><div>Add Order</div></a>
    <a href="view_order.php" class="option" id="option2" style="display: none;"><div>View Order</div></a>
    <a href="add_wholesalecustomer.php" class="option" id="option3" style="display: none;"><div>Add Customer</div></a>
    <a href="report.php" class="option" id="option4" style="display: none;"><div >Generate Report</div></a>
    <a href="summery.php" class="option" id="option5" style="display: none;"><div >Summary/Info</div></a>
    <a href="Admin_feed.php" class="option" id="option6" style="display: none;"><div>Distribute Products</div></a>
    <a href="create_order.php" class="option" id="option7" style="display: none;"><div >Pre Order</div></a>
    <a href="my_order.php" class="option" id="option8" style="display: none;"><div>My Orders</div></a>
    <a href="System_Manage.php" class="option" id="option9" style="display: none;"><div>System Manage</div></a>
    
</div>

    </div>

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
        document.addEventListener("DOMContentLoaded", function () {

        <?php if ($_SESSION["state"] === 'seller')  : ?>
            document.getElementById("option1").style.display = "block";
            document.getElementById("option2").style.display = "block";
            document.getElementById("option3").style.display = "block";
            document.getElementById("option4").style.display = "block";
            document.getElementById("option5").style.display = "block";
        <?php elseif ($_SESSION["state"] === 'admin'): ?>
            document.getElementById("option4").style.display = "block";
            document.getElementById("option5").style.display = "block";
            document.getElementById("option6").style.display = "block";
            document.getElementById("option9").style.display = "block";
            
        <?php elseif ($_SESSION["state"] === 'wholeseller'): ?>
            document.getElementById("option7").style.display = "block";
            document.getElementById("option8").style.display = "block";
        <?php endif; ?>

    });
     
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

    // Send a request to a PHP script that destroys the session
    xmlhttp.open("GET", "logout.php", true); //  logout.php is the script that destroys the session
    xmlhttp.send();
}
  function showSuccess() {
    var overlay = document.getElementById('overlay');
    var successModal = document.getElementById('successModel');

    overlay.style.display = 'block';
    successModal.style.display = 'block';
  }

  function hideSuccess() {
    var overlay = document.getElementById('overlay');
    var successModal = document.getElementById('successModel');

    overlay.style.display = 'none';
    successModal.style.display = 'none';
  }

function redirectToIndex() {
    hideSuccess();
    // Redirect to index.php
    window.location.href = 'index.php';
  }
  
</script>
</body>
</html>