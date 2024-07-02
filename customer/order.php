<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || $_SESSION["state"] != 'wholeseller') {
    acess_denie();
    exit();
} else {
    $_SESSION['manage_employee_visit'] = true;
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, maximum-scale=1.0, initial-scale=1, user-scalable=no">
    <title>Manage Orders</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/option.css">

</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <a href="javascript:void(0)" onclick="back()" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">manage orders</span></a>

        </div>
        <div class="options-container">
            <a href="/common/view_order.php" class="option" id="option1">
                <div>My Pre Orders</div>
            </a>
            <a href="/common/sale_order1.php" class="option" id="option1">
                <div>My Sale Orders</div>
            </a>

        </div>
    </div>


    <script>
        function back() {
            window.history.back();
        }
    </script>

</body>

</html>