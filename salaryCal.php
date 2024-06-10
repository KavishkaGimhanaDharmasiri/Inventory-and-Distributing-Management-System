<!DOCTYPE html>
<html>
	<head>
		<style>
			body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            background: radial-gradient(circle, rgba(76,175,80,1) 0%, rgba(247,252,248,1) 0%, rgba(250,253,251,1) 23%, rgba(252,254,253,1) 36%, rgba(255,255,255,1) 47%, rgba(246,251,246,1) 59%, rgba(228,243,229,1) 68%, rgba(171,218,173,1) 100%, rgba(76,175,80,1) 100%);
           
           
        }
		</style>
	</head>
<body>
<?php
// Include the database connection file
include("../conn/conn.php");

// Check if 'emp' and 'month' GET parameters are set
if (isset($_GET['emp'],$_GET['month'])) {
    $emp = $_GET['emp'];
    $month = $_GET['month'];
    
	$date = date('Y-m-d');

	        // Prepare and execute the SQL statement to fetch attendance data
			$stmt = $conn->prepare("select * from tbl_attendance left join tbl_employee on tbl_attendance.tbl_employee_id=tbl_employee.tbl_employee_id where tbl_attendance.tbl_employee_id = (:tbl_employee_id) and tbl_month= (:tbl_month); ");
			$stmt->bindParam(":tbl_employee_id", $emp);
			$stmt->bindParam(":tbl_month", $month);
			
			if($stmt->execute()){
				$result = $stmt->fetchAll();
				$hours=0;
				$employeeName = '';
				$monthT='';
				$noOfDays=0;
				$rateOfPay=0;
				$comRate=0;
				$basicSalary=13696.00;

				// Calculate total hours worked, days worked, and other payroll details
				foreach ($result as $row) {
					$employeeName = $row["employee_name"];
					$time_in = $row["time_in"];
					$time_out = $row["time_out"];
					$monthT=$row["tbl_month"];
					$start_time = DateTime::createFromFormat('Y-m-d H:i:s', $time_in);
					$end_time = DateTime::createFromFormat('Y-m-d H:i:s', $time_out);
						if ($start_time && $end_time) {
							$time_diff = $start_time->diff($end_time);

							$hours_diff = $time_diff->h; // Get hours portion of the difference
							$hours_diff += $time_diff->days * 24; // Add hours from full days (if any)
							$hours +=$hours_diff;
						}
					$noOfDays++;
					$rateOfPay= $row["hour_pay"];
					$comRate=$row["allowance"];
				}

				// Calculate payroll details
				$salary=$rateOfPay*$hours;
				$epf=$salary*8/100;
				$afterEpf=$salary-$epf;
				$commision=$salary*$comRate/100;
				$payBalance=$afterEpf+$commision+$basicSalary;

				// Display payroll details
				echo "<h2 align='center'>pay sheet </h2><br>";
				echo "<label>Employe name:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$employeeName."</label> <br>";
				echo "<label>Month :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$monthT."</label><br>";
				echo "<label>No of Hours :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$hours."</label><br>";
				echo "<label>No of Days Work:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;".$noOfDays."</label><br>";
				echo "<label>Rate Of Pay Hourly :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs.".$rateOfPay.".00</label><br>";
				echo "<label>Monthly Salary :&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbspRs.".$salary.".00</label><br>";
				echo "<label>EPF 08%:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs.".$epf.".00</label><br>";
				echo "<label>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs.".$afterEpf.".00</label><br>";
				echo "<label>Commision:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs.".$commision."</label><br>";
				echo "<label>Payment Baalance:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Rs.".$payBalance."</label><br>";
			}
	
    
} else {
    echo "No value selected.";
}

?>

<button class="btn"> Print </button>
</body>
</html>
