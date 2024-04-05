<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['qr_code'])) {
        $qrCode = $_POST['qr_code'];

        $selectStmt = $conn->prepare("SELECT tbl_employee_id FROM tbl_employee WHERE generated_code = :generated_code");
        $selectStmt->bindParam(":generated_code", $qrCode, PDO::PARAM_STR);

        if ($selectStmt->execute()) {
            $result = $selectStmt->fetch();
            if ($result !== false) {
                $employeeID = $result["tbl_employee_id"];
                $timeIn =  date("Y-m-d H:i:s");
            } else {
                echo "No employee found with the provided QR Code";
            }
        } else {
            echo "Failed to execute the statement.";
        }


        try {
            $stmt = $conn->prepare("INSERT INTO tbl_attendance_employee (tbl_employee_id, time_in) VALUES (:tbl_employee_id, :time_in)");
            
            $stmt->bindParam(":tbl_employee_id", $employeeID, PDO::PARAM_STR); 
            $stmt->bindParam(":time_in", $timeIn, PDO::PARAM_STR); 

            $stmt->execute();

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
