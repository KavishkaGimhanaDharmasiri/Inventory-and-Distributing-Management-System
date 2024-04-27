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
            background-color: white;
            color: white;
            text-decoration: none;
            position: relative;
            display: inline-block;
            border-radius: 2px;
        }

        .notification .badge {
            position: absolute;
            top: 0px;
            right: 1px;
            height: 2px;
            width: 2px;
            padding: 2px 2px;
            border-radius: 50%;
            background-color: red;
            color: white;
            font-size: 5px;
        }
    </style>
</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">

            <?php
            if (isset($_SERVER['HTTP_REFERER']) && !empty($_SERVER['HTTP_REFERER'])) {
                // Generate back navigation link using HTTP_REFERER
                echo '<a href="' . $_SERVER['HTTP_REFERER'] . '" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>';
            } else {
                // If no referrer is set, provide a default back link
                echo '<a href="javascript:history.go(-1);" class="back-link" style="float:left; font-size:30px;"><i class="fa fa-angle-left"></i></a>';
            }
            ?>
            <div id="mySidepanel" class="sidepanel">
                <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">×</a>
                <a href="#">About</a>
                <a href="#">Services</a>
                <a href="#">Clients</a>
                <a href="#">Contact</a>
            </div>

            <a href="javascript:void(0);" class="icon" onclick="openNav()">
                <span class="badge"></span>

            </a><i class="fa fa-bars"></i>
            </a>
        </div>
    </div>


    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }
    </script>

</body>

</html>