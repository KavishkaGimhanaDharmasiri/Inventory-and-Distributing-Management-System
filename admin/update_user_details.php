<?php
session_start();
include($_SERVER['DOCUMENT_ROOT'] . "/common/db_connection.php");
require_once($_SERVER['DOCUMENT_ROOT'] . "/common/den_fun.php");


if (!isset($_SESSION['option_visit']) || !isset($_SESSION['index_visit']) || !isset($_SESSION['manage_employee_visit']) || $_SESSION["state"] != 'admin') {
    acess_denie();
    exit();
} else {
    $_SESSION['update_user_details_visit'] = true;
}
?>
<!DOCTYPE html>
<html>

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Update User Details</title>
    <link rel="icon" href="/images/tab_icon.png">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="/style/style.css">
    <link rel="stylesheet" type="text/css" href="/style/mobile.css">
    <link rel="stylesheet" type="text/css" href="/style/divs.css">
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
                    <label for="address">User Category<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <select name="customers" id="customers">
                        <option value=""><b>Select Category<b></option>
                        <option value=""><b>Sales Person<b></option>
                        <option value=""><b>Wholesale Customer<b></option>

                    </select>
                </div>
                <div class="form-group">
                    <label for="address">User Name<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <select name="customers" id="customers">
                        <option value=""><b>Select Name Of User<b></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Update Category<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <select name="customers" id="customers">
                        <option value=""><b>Conatact No.<b></option>
                        <option value=""><b>Address<b></option>
                        <option value=""><b>Email Address<b></option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="address">Contact No.<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="contact_no" class="form-control" required placeholder="e.g:07XXXXXXXX">
                    <label for="address">Address<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="address" class="form-control" required placeholder="e.g:No.23, Samagi Mawatha, Beliatta">
                    <label for="address">Email Address<lable style="color: red; font-size: 14pt">&nbsp;*</label></label>
                    <input type="text" name="email_address" class="form-control" required placeholder="e.g:noreply@email.com">
                </div>
                <button type="submit">Update Details</button>
                <br>
                <button type="reset">Clear Data</button>
            </form>
        </div>
    </div>

    <script type="text/javascript" src="/javascript/divs.js"></script>
    </div>


    <script>
        function back() {
            window.history.back();
        }
    </script>

</body>

</html>