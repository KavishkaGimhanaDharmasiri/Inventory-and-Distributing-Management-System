<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['index_visit']) || !$_SESSION['index_visit'] || !isset($_SESSION["user_id"]) || !isset($_SESSION["state"])) {

  acess_denie();
  exit();
} else {
  unset($_SESSION['new_sale_order_visit']);
  unset($_SESSION['paymenr_visit']);

  $user_idn = $_SESSION["user_id"];

  $_SESSION['option_visit'] = true;
}
$query = "SELECT * FROM users WHERE user_id = '$user_idn'";
$result = mysqli_query($connection, $query);

$currentmonth = date('Y-m');

$sqlq = "SELECT * FROM payment WHERE balance > 4000 AND DATE_FORMAT(payment_date, '%Y-%m') = '$currentmonth'";
$results = $connection->query($sqlq);

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
unset($_SESSION['process_payment']);
//unset($_SESSION['sales_recipt_download']);
unset($_SESSION['send_massage']);

// End the session
session_write_close();

?>
<!DOCTYPE html>
<html>

<head>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <link rel="stylesheet" type="text/css" href="/style/mobile.css">
  <link rel="stylesheet" type="text/css" href="/style/style.css">
  <style>
    .options-container {
      background-color: #fff;
      border-radius: 15px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      padding: 20px;
      display: flex;
      flex-wrap: wrap;
      justify-content: space-around;
      margin-top: 5%;
    }

    .option {
      width: 75%;
      margin-bottom: 20px;
      background-color: #4caf50;
      /* background: linear-gradient(57deg, #35a844, #141514, #36a536);
            background-size: 180% 180%;
            animation: gradient-animation 6s ease infinite;*/
      color: #fff;
      text-align: center;
      padding: 15px;
      cursor: pointer;
      padding-right: 5px;

      border-radius: 20px;
      background: linear-gradient(300deg, #3bb52d, #3bb52d, #3bb52d, #fcfcfc, #33a133, #33a133);
      background-size: 360% 360%;
      animation: gradient-animation 12s ease infinite;
      color: black;
      font-weight: bold;
    }

    @keyframes gradient-animation {
      0% {
        background-position: 0% 50%;
      }

      50% {
        background-position: 100% 50%;
      }

      100% {
        background-position: 0% 50%;
      }
    }

    a {
      text-decoration: none;
      color: white;
      text-align: left;


    }

    .option:hover {
      background-color: #45a049;
    }

    #mynotification {
      background-color: transparent;
    }

    .notification {
      width: 0;
      position: fixed;
      z-index: 1;
      height: 100%;
      top: 0;
      right: 0;
      background-color: red;
      overflow-x: hidden;
      transition: 0.5s;
      padding-top: 40px;
      margin: auto;
    }

    .notification a {
      padding: 8px 8px 8px 20px;
      text-decoration: none;
      font-size: 15px;
      font-weight: bold;
      color: white;
      display: block;
      transition: 0.3s;
    }

    .notification a:hover {
      color: red;
    }

    .notification .closebtn {
      position: absolute;
      top: 0;
      right: 25px;
      font-size: 36px;
    }

    .notification-panel {
      background-color: #383938;
      border: 1px solid #ccc;
      border-radius: 10px;
      margin: 3px;
      padding: 5px;
      display: none;
    }

    #notifications {
      background-color: #fff;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
      font-size: 12px;
      padding: 10px;
      margin: 3px;
      height: 200px;
      color: black;
      display: none;

    }

    #notificationContent {
      background-color: #383938;
      border-radius: 10px;
      box-shadow: 0 0 10px black;
      font-size: 12px;
      color: white;
    }

    .contain {
      display: flex;
      justify-content: space-between;
      color: white;
    }

    .badge {
      position: absolute;
      right: 25px;
      padding: 4px 4px;
      border-radius: 50%;
      background-color: red;
      color: white;
    }
  </style>
</head>

<body>

  <!-- Simulate a smartphone / tablet -->
  <div class="mobile-container">

    <!-- Top Navigation Menu -->
    <div class="topnav">

      <?php
      // Generate back navigation link using HTTP_REFERER
      echo '<a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
      ?>
      <div id="mySidepanel" class="sidepanel" style="height:100%;">
        <a href="javascript:void(0)" style="color:white;font-size:13px;margin-top:10px;" class="closebtn" onclick="closeNav()">&#10005;</a>
        <a href="about.php">Info</a>
        <a href="#" onclick="toggleProfilePanel()">Profile</a>
        <a href="javascript:void(0)" onclick="opennot()">
          <?php
          if ($results->num_rows > 0) {
            echo '<span class="badge" id="bagesd" style="right: 40px;"></span>';
          }
          ?>Notification</a>
        <br>
        <br>
        <a href="javascript:void(0)" onclick="logout()">Logout</a>
      </div>
      <a href="javascript:void(0);" class="icon" onclick="openNav()">
        <i class="fa fa-bars"></i>
        <?php

        if ($results->num_rows > 0) {
          echo '<span class="badge" id="bages"></span>';
        }
        ?>
      </a>

      <div id="mynotification" class="notification">

        <div class="notification-panel" id="notificationPanel">
          <div class="contain">
            <a href style="font-size:12px;pointer-events: none;">Notification</a>
            <a href="javascript:void(0)" style="font-size:12px;cursor:pointer;" onclick="closenot()">&#10005;</a>
          </div>

          <div id="notificationContent" style="padding: 5px;">
            <div class="contain">
              <a href="javascript:void(0)" style="pointer-events: none;font-size:12px;font-weight:normal;">Some of These Customers have outstanding balance remaining</a>
              <a href="javascript:void(0)" style="font-size:12px;" onclick="hidenotifi()">&#10005;</a>
            </div>
            <div class="contain">

              <a href="javascript:void(0)" onclick="view_cus();" class="view" style="font-size: 11px;cursor:pointer;color:blue;">View detils</a>
              <a href="javascript:void(0)" onclick="sendSMS()" style="cursor:pointer;font-size:11px;color:blue;">Send Message</a>
            </div>

          </div>
        </div>
        <div id="notifications" style="max-height:100%;"></div>
      </div>


      <div id="profilePanel" class="profile-panel" style="max-height: 320px;">



        <div class="">
          <lable onclick="profileclose()" style="margin-left:90%;">&#10005;</lable>
          <div class="our-team">
            <div class="picture">
              <img class="img-fluid" style="height: 100px; width: 100px;" src="https://picsum.photos/id/77/1631/1102">
            </div>
            <div class="team-content">
              <?php echo '<lable style="font-weight:bold;font-size:16px;">' . $_SESSION["user_log_fname"] . " " . $_SESSION["user_log_lname"] . "</lable><br><br><lable style='font-weight:bold;font-size:14px;'>" . $_SESSION["state"] . "</label><br><br><lable style='font-weight:bold;font-size:14px;'>" . $_SESSION["user_log_email"] . '</label><br><br>'; ?>
              <button type="button" onclick="changePassword()" style="border-bottom-right-radius: 0px;border-top-right-radius: 0px; border-top-right-radius: 15px; padding: 5px;margin:0px;" class="changePass">Change Password</button><br>
              <button type="button " onclick="logout()" style="background: indianred;color:white; border-bottom-right-radius: 0px;border-top-right-radius: 0px; padding: 5px; border: 1px solid indianred;margin-top: 8px;border-bottom-right-radius: 15px">Logout</button>
            </div>
          </div>

        </div>
      </div>

    </div>

    <div class="options-container">

      <a href="/seller/new_order.php" class="option" id="option1" style="display: none;">
        <div>Add Order</div>
      </a>
      <a href="/common/manage_orders.php" class="option" id="option2" style="display: none;">
        <div>View Orders</div>
      </a>
      <a href="/seller/add_wholesalecustomer.php" class="option" id="option3" style="display: none;">
        <div>Add Customer</div>
      </a>
      <a href="/common/report.php" class="option" id="option4" style="display: none;">
        <div>Generate Report</div>
      </a>
      <a href="/seller/summery.php" class="option" id="option5" style="display: none;">
        <div>Summary/Info</div>
      </a>
      <a href="/seller/handle_return.php" class="option" id="option10" style="display: none;">
        <div>Return Items</div>
      </a>
      <a href="/admin/Admin_feed.php" class="option" id="option6" style="display: none;">
        <div>Distribute Products</div>
      </a>
      <a href="/customer/create_order.php" class="option" id="option7" style="display: none;">
        <div>Pre Order</div>
      </a>
      <a href="/customer/my_order.php" class="option" id="option8" style="display: none;">
        <div>My Orders</div>
      </a>
      <a href="/admin/System_Manage.php" class="option" id="option9" style="display: none;">
        <div>System Manage</div>
      </a>
      <a href="/seller/Transaction_setelement.php" class="option" id="option11" style="display: none;">
        <div>Payment Settlement</div>
      </a>

    </div>

  </div>


  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <script>
    function openNav() {
      document.getElementById("mySidepanel").style.width = "150px";
    }

    function closeNav() {
      document.getElementById("mySidepanel").style.width = "0px";
    }

    function opennot() {
      document.getElementById("mynotification").style.width = "280px";
      document.getElementById("notificationPanel").style.display = "block";
      document.getElementById("mySidepanel").style.width = "0px";
    }

    function closenot() {
      document.getElementById("mynotification").style.width = "0px";
    }

    function hidenotifi() {
      document.getElementById("notificationContent").style.display = "none";
      document.getElementById("bages").style.display = "none";
      document.getElementById("bagesd").style.display = "none";
      document.getElementById("notifications").style.display = "none";

    }
    document.addEventListener("DOMContentLoaded", function() {

      <?php if ($_SESSION["state"] === 'seller') : ?>
        document.getElementById("option1").style.display = "block";
        document.getElementById("option2").style.display = "block";
        document.getElementById("option3").style.display = "block";
        document.getElementById("option4").style.display = "block";
        document.getElementById("option5").style.display = "block";
        document.getElementById("option10").style.display = "block";
        document.getElementById("option11").style.display = "block";
      <?php elseif ($_SESSION["state"] === 'admin') : ?>
        document.getElementById("option4").style.display = "block";
        document.getElementById("option5").style.display = "block";
        document.getElementById("option6").style.display = "block";
        document.getElementById("option9").style.display = "block";
        document.getElementById("option5").style.display = "block";

      <?php elseif ($_SESSION["state"] === 'wholeseller') : ?>
        document.getElementById("option7").style.display = "block";
        document.getElementById("option8").style.display = "block";
      <?php endif; ?>

    });

    function back() {
      window.history.back();
    }

    function toggleProfilePanel() {
      var profilePanel = document.getElementById('profilePanel');
      profilePanel.style.display = (profilePanel.style.display === 'block') ? 'none' : 'block';
      document.getElementById("mySidepanel").style.width = "0px";
      //fetchUserData()
    }

    function profileclose() {
      const profilePanel = document.getElementById('profilePanel').style.display = "none";
    }

    function changePassword() {
      // Implement the logic to change the password here
      window.location.href = "/common/chng_pass.php";
    }

    function logout() {
      // AJAX request to a PHP script that handles session logout
      var xmlhttp = new XMLHttpRequest();
      xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
          // Redirect to login.php after successful logout
          window.location.href = "/index.php";
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
      window.location.href = "/index.php";
    }

    function sendSMS() {
      // Create an AJAX request
      var xhr = new XMLHttpRequest();

      // Define the PHP file and function to call
      var phpFile = "email_sms.php";
      var functionName = "sendsms";

      // Prepare the data to send
      var data = new FormData();
      data.append('functionName', functionName);

      // Configure the AJAX request
      xhr.open("POST", phpFile, true);

      // Set the event handler to manage the response
      xhr.onreadystatechange = function() {
        if (xhr.readyState == 4 && xhr.status == 200) {
          // Do something with the response
          console.log(xhr.responseText);
        }
      };

      // Send the request
      xhr.send(data);
    }

    function changePassword() {
      // Implement the logic to change the password here
      window.location.href = 'chng_pass.php';
    }
    $(document).ready(function() {
      // Load notification count
      $.ajax({
        url: 'fetch_notifications.php', // Change this to your PHP script that fetches the notification count
        type: 'POST',
        data: {
          action: 'get_notification_count'
        },
        success: function(data) {
          document.getElementById('badges').style.display = 'block';
        }
      });

      // When clicking on the notification bell
      function shownotf() {
        // Show the notification panel
        // $('.notification-panel').toggle();
        alert("hi");

      }

      $('.view').click(function() {
        document.getElementById("notifications").style.display = "block";
        $.ajax({
          url: 'fetch_notifications.php', // Change this to your PHP script that fetches the notifications
          type: 'POST',
          data: {
            action: 'get_notifications'
          },
          success: function(data) {
            $('#notifications').html(data);
          }
        });
      }) // Load notifications

    });
  </script>

</body>

</html>