<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
require $_SERVER['DOCUMENT_ROOT'] . '/common/notification_area.php';

if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit'])) {
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
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
            width: 45%;
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
            border-radius: 0;
            border-bottom-left-radius: 15px;
            border-top-right-radius: 15px;
            background: linear-gradient(300deg, #3bb52d, #3bb52d, #3bb52d, #fcfcfc, #33a133, #33a133);
            background-size: 360% 360%;
            animation: gradient-animation 12s ease infinite;
            color: black;
            font-weight: bold;
            border-left: 1px solid green;
            border-bottom: 1px solid green;

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


        }

        .option:hover {
            background-color: #45a049;
        }

        @media (max-width: 600px) {
            .option {
                width: 100%;
            }
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <?php
            topnavigation();
            ?>

            <a href="javascript:void(0);" class="icon" onclick="openNav()">
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="options-container">
            <a href="/common/view_order.php" class="option" id="option1">
                <div>View Customer Order</div>
            </a>
            <a href="/common/sale_order1.php" class="option" id="option1">
                <div>View Sales Orders</div>
            </a>

        </div>
    </div>

</body>

</html>