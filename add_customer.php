<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("db_connection.php");

require_once('email_sms.php');
$route_query = "SELECT * FROM route";
$result1 = mysqli_query($connection, $route_query);

if (!$result1) {
    echo("route query failed: " . mysqli_error($connection));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    // Extract data from the form
    $firstname = $_POST['firstname'];
    $lastname = $_POST['lastname'];
    $telephone = $_POST['telephone'];
    $address = $_POST['address'];


    if ($_POST['email'] === null || $_POST['email'] === "") {
        $email=null;
    }
    else{
        $email=$_POST['email'];
    }

    $storename = $_POST['storename'];
    $storeregno = $_POST['storeregno'];
    $storeaddress = $_POST['storeaddress'];
    $location = $_POST['location'];
    $route_id=$_POST['route'];
    $_SESSION['sale_route']=$route_id;
    

    // Insert data into the customer table
  try{
    $pdo->beginTransaction();

    $valid_query = "SELECT * FROM users WHERE  telphone_no= $telephone OR email='$email'";
    $result6 = mysqli_query($connection, $valid_query);
    

    if($result6===0){

    // Insert data into the customer table
    $customer_insert_query = "INSERT INTO users (firstName, LastName, telphone_no, Address, email) 
                         VALUES (:firstName, :LastName, :telphone_no, :Address, :email)";

    $stmt2 = $pdo->prepare($customer_insert_query);
      $stmt2->bindParam(':firstName', $firstname);
      $stmt2->bindParam(':LastName', $lastname);
      $stmt2->bindParam(':telphone_no', $telephone);
      $stmt2->bindParam(':Address', $address);
      $stmt2->bindParam(':email', $email);
      $stmt2->execute();

      $user_id = $pdo->lastInsertId();

    if ($_SESSION["state"] === 'admin'){
        $route_sale=$_SESSION['sale_route'];
        $cus_state="seller";
    
    }
    else if ($_SESSION["state"] === 'seller'){
        $route_sale=$_SESSION['route_id'];
        $cus_state="wholeseller";

        $store_insert_query = "INSERT INTO customers (user_id, route_id, sto_reg_no, sto_tep_number, sto_name, sto_loc) 
                           VALUES (:user_id, :route_id, :sto_reg_no, :sto_tep_number, :sto_name, :sto_loc)";

        $stmt3 = $pdo->prepare($store_insert_query);
          $stmt3->bindParam(':user_id', $user_id);
          $stmt3->bindParam(':route_id', $route_sale);
          $stmt3->bindParam(':sto_reg_no', $storeregno);
          $stmt3->bindParam(':sto_tep_number',$telephone);
          $stmt3->bindParam(':sto_name', $storename);
          $stmt3->bindParam(':sto_loc', $location);
          $stmt3->execute();
    }
    // Insert data into the store table
    $lastFiveDigits = substr((string)$telephone, -5);
    
    // Insert data into the login table
     $login_insert_query = "INSERT INTO login (user_id, username, password, state, Active_state,route_id) VALUES (:user_id, :username, :password, :state, :Active_state,:route_id)";


          $stmt4 = $pdo->prepare($login_insert_query);
          $stmt4->bindParam(':user_id', $user_id);
          $stmt4->bindParam(':username', $firstname);
          $stmt4->bindParam(':password', $lastFiveDigits);
          $stmt4->bindParam(':state',$cus_state);
          $stmt4->bindParam(':Active_state', NULL);
          $stmt4->bindParam(':route_id', $route_sale);
          $stmt4->execute();
          $login_insert=mysqli_query($connection, $login_insert_query);
          $modifiedNumber = '94' . substr($telephone, 1);

}
        $pdo->commit();
         } catch (Exception $e) {
    // Rollback the transaction in case of an error
    $pdo->rollBack();
    echo "Failed: " . $e->getMessage();
    }
//sending sms and email
    $Subject='Welcome to Lotus Electicals (PVT)LTD';
    $body="\nDear $firstname,\n\n"
                . "Thank you for registering with YourSite.\n"
                . "Your username is: $firstname\n"
                . "Your generated password is: $lastFiveDigits\n"
                . "Please keep your login details secure.\n\n"
                . "Best regards,\nLotus Electicals (PVT)LTD";

    // Set the email subject and body
 $headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n"; 
sendmail($Subject,$body,$email,$firstname);

//sending sms to customer
$message=$Subject.$body;
$smsbody = urlencode($message);

sendsms($modifiedNumber,$smsbody);
echo '<div id="overlay"></div><div id="successModal"><div class="gif"></div>
                    <button onclick="redirectToIndex()" class="sucess">OK</button>
                    </div>';
mysqli_close($connection);
}
       
if (isset($error_message)) {
            echo '<div class="alert alert-danger">' . $error_message . '</div>';
        }

$userState = isset($_SESSION["state"]) ? $_SESSION["state"] : '';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" type="text/css" href="style.css">
    <link rel="stylesheet" type="text/css" href="divs.css">
    <link rel="stylesheet" type="text/css" href="add_wholesalecustomer.css">
    <title>Login</title>
</head>
<body>
    <div class="container" >
        <h2 id="customer_data">Customer Details</h2>
        <h2 id="sales_data" style="display: none;">Sales Person Details</h2>

       
        <form method="POST" action="<?php echo $_SERVER["PHP_SELF"]; ?>" onsubmit="return validateForm()">
            <!-- Customer Details Section -->
            <div class="form-group">
              <label for="Route_name" id="Route_name" style="display: none;"><b>Route<b></label>
            <select name="route" id="route" style="display: none;" required>
                <option value="">Select Route</option>
                <?php
                while ($row = mysqli_fetch_assoc($result1)) {
                    $selected = (isset($_POST['route']) && $_POST['route'] == $row['route']) ? 'selected' : '';
                    echo "<option value='{$row['route_id']}' $selected>{$row['route']}</option>";
                }
                ?>

            </select>
        </div>
            <div class="form-group">
                <label for="firstname"><b>First Name</label>
                <input type="text" name="firstname" class="form-control" required placeholder="e.g:Sandaruwan" >
            </div>
            <div class="form-group">
                <label for="lastname">Last Name</label>
                <input type="text" name="lastname" class="form-control" required placeholder="e.g:Perera">
            </div>
            <div class="form-group">
                <label for="telephone">Telephone Number</label>
                <input type="number" name="telephone" class="form-control" required maxlength="11" placeholder="07XXXXXXXX">
            </div>
            <div class="form-group">
                <label for="address">Address</label>
                <input type="text" name="address" class="form-control" required placeholder="e.g:No.23, Samagi Mawatha, Beliatta">
            </div>
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" name="email" class="form-control" placeholder="example@noreply.com">
            </div>

            <hr id="sep_hr"> <!-- Separate Customer and Store Details -->

            <!-- Store Details Section -->
            <h2 id="sto_detail">Store Details</h2>
            <div class="form-group">
                <label for="storename" id="storenamelable">Store Name</label>
                <input type="text" name="storename" id="storename" class="form-control" placeholder="Sadaruwan Hardware">
            </div>
            <div class="form-group">
                <label for="storeregno" id="storeregnolable">Store Reg. No.</label>
                <input type="text" name="storeregno" id="storeregno" class="form-control" placeholder="LK-8956">
            </div>
            <div class="form-group">
                <label for="storeaddress" id="storeaddresslable">Store Address</label>
                <input type="text" name="storeaddress" id="storeaddress" class="form-control" placeholder="e.g:No.54, Main Street, Beliatta">
            </div>
            <div class="form-group">
                <label for="location" id="storelocnolable">Store Location</label>
                <input type="text" name="location" id="location" class="form-control" placeholder="e.g:Hambantota">
            </div>

            <?php if ($userState === 'seller'): ?>
                <button type="submit" name="add_whole_seller">Add Whole Seller</button>
            <?php endif; ?>

            <!-- Add Sales Person Button -->
            <?php if ($userState === 'admin'): ?>
                <button type="submit" name="add_sales_person">Add Sales Person</button>
            <?php endif; ?>
            <br><br>
            <button type="reset">Clear Data</button> 
        </form>
    </div>
    <script type="text/javascript" src="divs.js"></script>
    <script type="text/javascript">
 
   document.addEventListener("DOMContentLoaded", function () {
            var userState = "<?php echo $userState; ?>";

            // Hide/show form elements based on user state
            if (userState === 'admin') {
                document.getElementById("storenamelable").style.display = "none";
                document.getElementById("storeregnolable").style.display = "none";
                document.getElementById("storelocnolable").style.display = "none";    
                document.getElementById("storeaddresslable").style.display = "none";
                document.getElementById("storename").style.display = "none";
                document.getElementById("storeregno").style.display = "none";
                document.getElementById("storeaddress").style.display = "none";
                document.getElementById("location").style.display = "none";
                document.getElementById("sto_detail").style.display = "none";
                document.getElementById("sep_hr").style.display = "none";
                document.getElementById("customer_data").style.display = "none";
                 document.getElementById("Route_name").style.display = "block";
                document.getElementById("route").style.display = "block";
                document.getElementById("sales_data").style.display = "block";
                // Hide other relevant elements
            } else if (userState === 'seller') {
                // Hide elements related to admins
                document.getElementById("whole_persion").style.display = "none";
                
                
            }
        });
        function validateForm() {
            // Check if first name contains only letters
            var firstname = document.forms[0]["firstname"].value;
            if (!/^[A-Za-z]+$/.test(firstname)) {
                alert("Please enter only letters for First Name.");
                return false;
            }

            // Check if last name contains only letters
            var lastname = document.forms[0]["lastname"].value;
            if (!/^[A-Za-z]+$/.test(lastname)) {
                alert("Please enter only letters for Last Name.");
                return false;
            }


            // Check if telephone number is valid
         var telephone = document.forms[0]["telephone"].value;
    if (!/^\d{10,11}$/.test(telephone)) {
        alert("Please enter a valid 10 or 11-digit phone number for Telephone Number.");
        return false;
    }

            return true; // Form is valid
        }
    </script>
</body>
</html>