<?
session_start();
if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || $_SESSION["state"] != 'admin') {
    acess_denie();
    exit();
} else {
    $_SESSION['system_manage_visit'] = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-sale=1">
    <title>System Manage</title>
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
            <a href="javascript:void(0)" class="back-link" style="font-size: 20px;"><i class="fa fa-angle-left" onclick="back()" style="float:left;font-size:25px;"></i><b>&nbsp;&nbsp;&nbsp;<span style="font-size: 17px;">manage system</span></a>
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