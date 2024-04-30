<html>
<title>

</title>
<link href="mobile.css" />

<body>
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
</body>

</html>