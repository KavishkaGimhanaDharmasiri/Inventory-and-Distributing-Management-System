 <?php 
    session_start();
    include("db_connection.php");
    require_once('den_fun.php');
    if(!isset($_SESSION['index_visit']) ||  !isset($_SESSION['option_visit']) || !isset($_SESSION["user_id"] ) || !isset($_SESSION["state"]) ){
    
        acess_denie();
    exit();

}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Include your database connection file
    
    try{
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
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<link rel="stylesheet" type="text/css" href="divs.css">
	<style type="text/css">
		body{
			height: 100vh;
		}
	</style>
</head>
<body>
	<div class="container">
		<form method="POST" action="add_Route.php">
		<div class="form-group">
                <label for="address">Route Name</label>
                <input type="text" name="route_name" class="form-control" required placeholder="e.g:Jaffna">
        </div>
        <div class="form-group">
                <label for="address">Vehicle No.</label>
                <input type="text" name="vehicle_no" class="form-control" required placeholder="e.g:LN-XXX">
        </div>
         <button type="submit">Add Route to System</button>
            <br><br>
            <button type="reset">Clear Data</button>
        </form>
    </div>
   
 <script type="text/javascript" src="divs.js"></script>
 <script type="text/javascript">
 	function redirectToIManage() {
    hideSuccess();
    // Redirect to index.php
    window.location.href = 'System_Manage.php';
  }
 </script>
</body>
</html>