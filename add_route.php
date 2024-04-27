<?php
session_start();
include("db_connection.php");
require_once('den_fun.php');
if (!isset($_SESSION['index_visit']) ||  !isset($_SESSION['option_visit']) || !isset($_SESSION["user_id"]) || !isset($_SESSION["state"])) {

    acess_denie();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file

    try {
        $pdo->beginTransaction();
        // Extract data from the form
        $routename = $_POST['route_name'];
        $vehicleNo = $_POST['vehicle_no'];

        $query2 = "INSERT INTO route(route, vehicle_no) 
                       VALUES (:route, :vehicle_no)";

        $stmt2 = $pdo->prepare($query2);
        $stmt2->bindParam(':route', $routename);
        $stmt2->bindParam(':vehicle_no', $vehicleNo);
        $stmt2->execute();
        // Commit the transaction
        $pdo->commit();
        echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div><button onclick="redirectToIManage()" class="sucess">OK</button>
                    </div>';
    } catch (Exception $e) {
        // Rollback the transaction in case of an error
        $pdo->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}

?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="divs.css">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="mobile.css">

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
                <i class="fa fa-bars"></i>
            </a>
        </div>
        <div class="container">
            <form method="POST" action="add_Route.php">
                <div class="form-group">
                    <label for="address">Route Name<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="route_name" class="form-control" required placeholder="e.g:Jaffna">
                </div>
                <div class="form-group">
                    <label for="address">Vehicle No.<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="vehicle_no" class="form-control" required placeholder="e.g:LN-XXXX">
                </div>
                <button type="submit">Add Route to System</button>
                <br>
                <button type="reset">Clear Data</button>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="divs.js"></script>
    <script>
        function openNav() {
            document.getElementById("mySidepanel").style.width = "150px";
        }

        function closeNav() {
            document.getElementById("mySidepanel").style.width = "0";
        }

        function redirectToIManage() {
            hideSuccess();
            // Redirect to index.php
            window.location.href = 'System_Manage.php';
        }
    </script>

</body>

</html>