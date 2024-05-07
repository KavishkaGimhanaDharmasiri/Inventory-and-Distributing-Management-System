<?
session_start();
if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || $_SESSION["state"] != 'admin') {
    acess_denie();
    exit();
} else {
    $_SESSION['system_manage_visit'] = true;
}
?>
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-sale=1">
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
            color: #fff;
            text-align: center;
            padding: 15px;
            cursor: pointer;
            padding-right: 5px;
            border-radius: 0;
            border-radius: 15px;
            background: linear-gradient(300deg, #3bb52d, #3bb52d, #3bb52d, #fcfcfc, #33a133, #33a133);
            background-size: 360% 360%;
            animation: gradient-animation 12s ease infinite;

        }

        a {
            text-decoration: none;
            color: white;
            text-align: center;


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
            // Generate back navigation link using HTTP_REFERER
            echo '<a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            ?>

        </div>
        <div class="options-container">
            <a href="add_Route.php" class="option" id="option2">
                <div>Add Route</div>
            </a>
            <a href="manage_employee.php" class="option" id="option3">
                <div>Manage Employee</div>
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