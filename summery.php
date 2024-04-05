<?php 
session_start();
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title></title>
	<link rel="stylesheet" type="text/css" href="style.css">
	<style type="text/css">
		.container {
		    display: flex;
		    flex-direction: column;
		    justify-content: center;
		    align-items: center;
		    height: 100vh;
		  }
  
		  .box {
		    background-color: #fff;
            border-radius: 15px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 400px;
            border: 1px solid #45a049;
		  }
		  table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 8px;
	        }

	        th, td {
	            padding: 6px;
	            border: 1px solid #ddd;
	            text-align: left;
	        }

	        th {
	            background-color: #4caf50;
	            color: #fff;
	        }
	        h4{
	        	text-align: left;
	        	color: black ;
	        	margin-bottom: 0px;
	        	margin-top: 2px;
	        }
	            input[type='checkbox'] {
    accent-color: #45a049;
    }
	</style>
</head>
<body>
<?php
	include("db_connection.php");
	$routeId =$_SESSION['route_id'];
	$currentMonthYear = date('Y-m');
//$_SESSION["state"]
		if("seller"==="seller"){
			$rem="route_id = $routeId AND";
		}
		else{
			$rem='';
		} 
		//order_state = 'complete'
		$sql = "SELECT DISTINCT(store_name),order_state FROM primary_orders WHERE order_type = 'sale' AND route_id=$routeId AND DATE_FORMAT(ord_date, '$currentMonthYear')";
		$result = mysqli_query($connection, $sql);
		$sql1 = "SELECT sto_name FROM customers WHERE route_id=$routeId";
		$result1 = mysqli_query($connection, $sql1);

		$sql3 = "SELECT sum(total) sum1,sum(payment_amout) as sum2, sum(balance) as sum3 FROM payment WHERE route_id=$routeId AND DATE_FORMAT(payment_date, '$currentMonthYear')";
		$result3 = mysqli_query($connection, $sql3);

		echo'<div class="containers">';
		
		echo "<form action='summery.php' method='post'>";
		echo'<div class="box">';
		echo "<h4>Order State of Your Route</h4><br>";
		echo "<table>";

		while ($row1 = mysqli_fetch_assoc($result1)) {
		    $storeNamed = $row1['sto_name'];
		    echo "<tr>";
		    	echo "<td><input type='checkbox' name='selected_stores' id='select_sto' readonly></td>";
			    echo "<td><b>".$storeNamed."</td>";
			    echo "<td><b>".""."</td>";
			    echo "</tr>";
		    

			while ($row = mysqli_fetch_assoc($result)) {
			 
		    
		    $storeName = $row['store_name'];
		    $storests = $row['order_state'];

				    if ($storests === 'complete') {
				    	echo "<tr>";
				    	echo "<td><input type='checkbox' name='selected_stores' id='select_sto'  checked readonly></td>";
					    echo "<td><b>".$storeName."</td>";
					    echo "<td><b>".$storests."</td>";
					    echo "</tr>";
				    }
				    else{
				    	echo "<td><input type='checkbox' name='selected_stores' id='select_sto'  checked readonly></td>";
				    echo "<td>".$storeName."</td>";
				    echo "<td><b>Not Complete"."</td>";
				    echo "</tr>";
				    }

		}
	}

		echo "</table>";
		echo "</div>";
echo'<br>';

		echo'<div class="box">';
		echo "<h4>Remaining Product Allocated Your Route</h4><br>";
		$sql2 = "SELECT f.feed_id,f.route_id,f.feed_date,i.sub_cat,i.main_cat,i.count FROM feed f left join feed_item i on i.feed_id=f.feed_id WHERE route_id=$routeId AND DATE_FORMAT(feed_date,'$currentMonthYear')";
		$result2 = mysqli_query($connection, $sql2);
		echo "<table>";
		echo "<tr>
		<th>Main Product</th><th>Sub Products</th><th>Count</th></tr>";
		while($row2 = mysqli_fetch_assoc($result2)) {
			echo "<tr>";
            echo "<td><b>" . $row2["main_cat"] . "</td>";
            echo "<td><b>" . $row2["sub_cat"] . "</td>";
            echo "<td><b>" . $row2["count"] . "</td>";
            echo "</tr>";
		}
		echo "</table>";

		echo "</div>";
echo'<br>';
		echo'<div class="box">';
		echo "<h4>Sales of Your Route(Current Month - $currentMonthYear)</h4><br>";
		echo "<table>";
		while($row3 = mysqli_fetch_assoc($result3)) {
			echo "<tr>";
            echo "<td><b>Total Sales</td>";
            echo "<td><b>Rs." . $row3["sum1"] . ".00</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Total Income </td>";
            echo "<td><b>Rs." . $row3["sum2"] . ".00</td>";
            echo "</tr>";
            echo "<tr>";
            echo "<td><b>Total Outstanding</td>";
            echo "<td><b>Rs." . $row3["sum3"] . ".00</td>";
            echo "</tr>";
		}
		echo "</table>";

		echo "</div>";

		echo "</div>";
		echo "</form>";

		// Close the database connection
		mysqli_close($connection);
?>

</body>
</html>