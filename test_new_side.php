<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        .mobile-container {
            max-width: 480px;
            margin: auto;
            background-color: white;
            height: 100vh;
            border-radius: 10px;
        }

        .topnav {
            overflow: hidden;
            background-color: white;
            position: relative;
            color: black;


        }

        .topnav a {
            color: black;
            padding: 14px 16px;
            text-decoration: none;
            font-size: 17px;
            display: block;
        }

        .topnav a.icon {
            background: white;
            display: block;
            position: absolute;
            right: 0;
            top: 0;
            color: black;
        }

        .sidepanel {
            width: 0;
            position: fixed;
            z-index: 1;
            height: 150px;
            top: 0;
            right: 0;
            background-color: black;
            overflow-x: hidden;
            transition: 0.5s;
            padding-top: 40px;
            margin: auto;
        }

        .sidepanel a {
            padding: 8px 8px 8px 20px;
            text-decoration: none;
            font-size: 15px;
            font-weight: bold;
            color: #818181;
            display: block;
            transition: 0.3s;
        }

        .sidepanel a:hover {
            color: #f1f1f1;
        }

        .sidepanel .closebtn {
            position: absolute;
            top: 0;
            right: 25px;
            font-size: 36px;
        }

        .notification {
            width: 0;
            position: fixed;
            z-index: 1;
            height: 100%;
            top: 0;
            right: 0;
            background-color: wheat;
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
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <?php
            echo '<a href="javascript:void(0)" onclick="back()" class="back-link" style="float:left; font-size:18pt;cursor:pointer;"><i class="fa fa-angle-left"></i></a>';
            ?>
            <a href="javascript:void(0);" class="icon" onclick="openNav()">
                <i class="fa fa-bars"></i>
        </div>
        <div id="mySidepanel" class="sidepanel">
            <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
            <a href="#">About</a>
            <a href="#">Services</a>
            <a href="#">Clients</a>
            <a href="javascript:void(0)" onclick="opennot()">massage</a>
            <br>
            <br>
            <a href="#" onclick="logout()">Logout</a>
        </div>
        <div id="mynotification" class="notification">
            <a href="javascript:void(0)" class="closebtn" style="color: black;" onclick="closenot()">×</a>
            <div class="notification-panel" id="notificationPanel">
                <table>
                    <tr>
                        <td style="font-weight: bold;">Notification</td>
                        <td style="width:80%;margin:0;float:right;">
                    </tr>
                </table>
                <div id="notificationContent">Some of These Customers have outstanding balance remaining<table>
                        <tr>
                            <td><a href="javascript:void(0)" onclick="view_cus();" class="view" style="font-size: 11px;cursor:pointer;color:blue;">View detils</a></td>
                            <td><a href="javascript:void(0)" onclick="sendSMS()" style="cursor:pointer;-webkit-user-select:none;font-size:11px;color:blue;">Send Message</a>
                            </td>
                        </tr>
                    </table>
                    <div id="notifications" style="border: 1px solid red;"></div>
                    <div id="sub"></div>
                </div>
            </div>
        </div>

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
                document.getElementById("mySidepanel").style.width = "150px";
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

            // When clicking on the notification bell

            function view_cus() {
                alert("hi");
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
            };

            function back() {
                window.history.back();
            }
        </script>

</body>

</html>