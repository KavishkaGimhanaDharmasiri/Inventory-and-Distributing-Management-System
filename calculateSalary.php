<?php
// Include the database connection file
include("../conn/conn.php");

// Check if the request method is POST (not GET as stated in your code)
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Check if the 'qr_code' POST parameter is set
    if (isset($_POST['qr_code'])) {
        $qrCode = $_POST['qr_code'];

        // Prepare the SQL statement to select the employee based on the QR code
        $selectStmt = $conn->prepare("SELECT tbl_employee_id FROM tbl_employee WHERE generated_code = :generated_code");
        $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);

        // Execute the statement and check if an employee is found
        if ($selectStmt->execute()) {
            $result = $selectStmt->fetch();
            if ($result !== false) {
                $employeeID = $result["tbl_employee_id"];
            } else {
                echo "No employee found with the provided QR Code";
            }
        } else {
            echo "Failed to execute the statement.";
        }


        try {
			$date = date('Y-m-d');
            // Prepare the SQL statement to check if an attendance record already exists for the employee today
			$stmt = $conn->prepare("select * from tbl_attendance where tbl_employee_id = (:tbl_employee_id) and tbl_month= (:tbl_month) and tbl_day= (:tbl_day); ");
			$stmt->bindParam(":tbl_employee_id", $employeeID);
			$stmt->bindParam(":tbl_month", explode('-',$date)[1]);
			$stmt->bindParam(":tbl_day", explode('-',$date)[2]);
			
			if($stmt->execute()){
				$result = $stmt->fetch();
				
				if ($result !== false) {
                     // If an attendance record exists, update the time_out
					$tbl_attendance_id = $result["tbl_attendance_id"];
					$updateStmt = $conn->prepare("update tbl_attendance set time_out=now() where tbl_attendance_id = (:tbl_attendance_id);");
					$updateStmt->bindParam(":tbl_attendance_id", $tbl_attendance_id, PDO::PARAM_STR);
					$updateStmt->execute();
					echo 'upddated ssucceffully';
				} else {
                    // If no attendance record exists, insert a new one
					$stmt = $conn->prepare("INSERT INTO tbl_attendance (tbl_employee_id),(tbl_day),(tbl_month) VALUES (:tbl_employee_id, :tbl_day, :tbl_month)");
					
					$stmt->bindParam(":tbl_employee_id", $employeeID, PDO::PARAM_STR);
					$stmt->bindParam(":tbl_day",explode('-',$date)[2]);
					$stmt->bindParam(":tbl_month", explode('-',$date)[1]);
					$stmt->execute();
				}
			}
			
            

            // Redirect to the index page after processing
            header("Location: http://localhost/employee-attendance-system/index.php");

            exit();
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/employee-attendance-system/index.php';
            </script>
        ";
    }
}
?>
