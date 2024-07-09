
<?php
session_start();
$total=$_SESSION['subtotal'];
$un=$_SESSION['email'];

 ?>
<html>
<head>
<style type>
	.popup{
		width: 400px;
		background: #fff;
		border-radius: 6px;
		position: absolute;
		top:50%;
		left: 50%;
		transform: translate(-50%, -50%);
		text-align: center;
		padding: 0 30px 30px;
		color: #333;
	}

	.popup img{
		width: 200px;
		height: 200px;
		margin-top:-50px;
		border-radius: 100px;
		box-shadow: 0 2px 5px rgba(0,0,0,0.2);
	}

	.popup h2{
		font-size: 38px;
		font-weight: 500;
		margin: 30px 0 10px;

	}

/*	.popup button{
		width: 100%;
		margin-top: 50px;
		padding: 10px 0;
		background: #6fd649;
		color: #fff;
		border: 0;
		outline: none;
		font-size: 18px;
		border-radius: 4px;
		cursor: pointer;
		box-shadow: 0 5px 5px rgba(0,0,0,0.2);
	}*/
</style>
</head>
<body>
	<div class="popup">
		<img src="Images/Decoration/check.gif">
		<h2>Thank you!</h2>
		<p>Your order has been successfully placed. Thank you for shopping with us.</p>
		<!-- <a href="Cart.php"><button type="button">OK</button></a> -->
	</div>

	<?php
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'retail_website';
$date = date('Y-m-d');
$conn = new mysqli($host, $username, $password, $database);

    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    $query = "SELECT userid FROM user_details WHERE email = ?";
				$stmt = $conn->prepare($query);

				if (!$stmt) {
				    die("Prepare failed: " . $conn->error);
				}
				// Bind the cus_id parameter
				$stmt->bind_param("s", $un );
				// Execute the query
				$stmt->execute();
				// Get the result set
				$result = $stmt->get_result();

				if ($result->num_rows > 0) {
				    // Fetch the result as an associative array
				    $row = $result->fetch_assoc();
				    $cus_id = $row['userid'];



								$query = "SELECT * FROM cart WHERE cus_id = ?";
								$stmt = $conn->prepare($query);

								if (!$stmt) {
								    die("Prepare failed: " . $conn->error);
								}
								// Bind the cus_id parameter
								$stmt->bind_param("s", $cus_id );
								// Execute the query
								$stmt->execute();
								// Get the result set
								$result = $stmt->get_result();

								if ($result->num_rows > 0) {
								    // Fetch the result as an associative array
								    while ($row = $result->fetch_assoc()) {
								    $product_id = $row['product_id'];
								    $quantity = $row['quantity'];



								    	$query = " INSERT INTO orders (cus_id,date, price) 
    										VALUES (?, ?,?)";

    										$stmt = $conn->prepare($query);

											if (!$stmt) {
											    die("Prepare failed: " . $conn->error);
											}

											// Bind the parameters
											$stmt->bind_param(
											    "iss", $cus_id,$date,$total,);
											    if ($stmt->execute()) {

} else {
    echo "Error: " . $stmt->error;
}





				}
			}
		}


				    ?>
</body>

</html>