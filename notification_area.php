<style>
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

    }

    #mynotification {
        background-color: transparent;
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

    .notification {
        width: 0;
        position: fixed;
        z-index: 1;
        height: 100%;
        top: 0;
        right: 0;
        background-color: transparent;
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
        color: red;
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
        display: none;
        background-color: white;
        border: 1px solid #ccc;
        border-radius: 10px;
        margin: 3px;
        padding: 5px;
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
        background-color: #fff;
        border-radius: 10px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        font-size: 12px;
        padding: 10px;
    }

    #noti {

        margin: 0;
    }

    #noti:hover {
        background-color: transparent;
    }

    table {
        margin-top: 0px;
    }

    .contain {
        display: flex;
        justify-content: space-between;
    }
</style>
<?php

function topnavigation()
{

    // If no referrer is set, provide a default back link
    echo '<a onclick="back()" class="back-link" style="float:left; font-size:18pt;cursor:pointer;"><i class="fa fa-angle-left"></i></a>';


    echo '<a href="javascript:void(0);" class="icon" onclick="openNav()">
    <i class="fa fa-bars"></i>
  </a>';
    echo '<div id="mySidepanel" class="sidepanel" style="height:100%;"
    <a href="javascript:void(0)" style="color:white;" class="closebtn" onclick="closeNav()">×</a>
    <a href="#">About</a>
    <a href="#">Services</a>
    <a href="#" onclick="toggleProfilePanel()">Profile</a>
    <a href="javascript:void(0)" onclick="opennot()">Notification</a>
    <br>
    <br>
    <a href="#" onclick="logout()">Logout</a>
  </div>';

    echo '<div id="mynotification" class="notification">

<div class="notification-panel" id="notificationPanel">
    <div class="contain">
            <a href style="font-size:12px;pointer-events: none;">Notification</a>
            <a href="javascript:void(0)" style="color: black;font-size:12px;" onclick="closenot()">&#10005;</a>
        </div>
    <div id="notificationContent" style="padding: 0px;">Some of These Customers have outstanding balance remaining<table>
    <div class="contain">
                <a href="javascript:void(0)" onclick="view_cus();" class="view" style="font-size: 11px;cursor:pointer;color:blue;">View detils</a>
                <a href="javascript:void(0)" onclick="sendSMS()" style="cursor:pointer;-webkit-user-select:none;font-size:11px;color:blue;">Send Message</a>
               
        </table>
        
    </div>
</div>
<div id="notifications" style="max-height:100%;"></div>
</div>';






    echo '<div id="profilePanel" class="profile-panel">

    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
      <div class="our-team">
        <div class="picture">
          <img class="img-fluid" style="height: 100px; width: 100px;" src="https://picsum.photos/id/77/1631/1102">
        </div>';
    echo '<div class="team-content">';
    echo '<h4 class="name">' . $_SESSION["user_log_fname"] . " " . $_SESSION["user_log_lname"] . '</h4>
          <h4 class="title">' . $_SESSION["state"] . '</h4><h4 class="title">' . $_SESSION["user_log_email"] . '</h4>';
    echo '<a href="chang_pass.php" style="color:green;">Change Password</a>';
    echo '<a href="javascript:void(0)" onclick="logout()" name="logout" id="logout" style="color:red;margin-top:none;" >Logout</a>';
    echo ' </div>';
    echo '</div>';

    echo '</div>';



    echo '</div>';
}
?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script type="text/javascript">
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
        document.getElementById("mySidepanel").style.width = "150px";
    }

    function back() {
        window.history.back();
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

    function toggleProfilePanel() {
        var profilePanel = document.getElementById('profilePanel');
        profilePanel.style.display = (profilePanel.style.display === 'block') ? 'none' : 'block';
        //fetchUserData()
    }

    function changePassword() {
        // Implement the logic to change the password here
        window.location.href = 'chng_pass.php';
    }
</script>