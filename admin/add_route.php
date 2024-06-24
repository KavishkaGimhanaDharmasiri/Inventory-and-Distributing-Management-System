<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");
if (!isset($_SESSION['index_visit']) ||  !isset($_SESSION['option_visit']) || !isset($_SESSION["user_id"]) || !isset($_SESSION["state"]) || $_SESSION["state"] != 'admin') {

    acess_denie();
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST"  && $_SESSION["state"] == 'admin') {
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
    <title>Add Route</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="/style/divs.css">
    <link rel="stylesheet" type="text/css" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">

</head>

<body>

    <!-- Simulate a smartphone / tablet -->
    <div class="mobile-container">

        <!-- Top Navigation Menu -->
        <div class="topnav">



            <a href="javascript:void(0);" onclick="back()" class="back-link" style="float:left;font-size:25px; "><i class="fa fa-angle-left"></i></a>


        </div>
        <div class="container">
            <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
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

    <script type="text/javascript" src="/javascript/divs.js"></script>
    <script>
        function redirectToIManage() {
            hideSuccess();
            // Redirect to index.php
            window.location.href = 'System_Manage.php';
        }

        function back() {
            window.history.back();
        }
    </script>

</body>

</html>