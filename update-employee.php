<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['employee_name'], $_POST['department'])) {
        $employeeId = $_POST['tbl_employee_id'];
        $employeeName = $_POST['employee_name'];
        $department = $_POST['department'];

        try {
            $stmt = $conn->prepare("UPDATE tbl_employee SET employee_name = :employee_name, department = :department WHERE tbl_employee_id = :tbl_employee_id");
            
            $stmt->bindParam(":tbl_employee_id", $employeeId, PDO::PARAM_STR); 
            $stmt->bindParam(":employee_name", $employeeName, PDO::PARAM_STR); 
            $stmt->bindParam(":department", $department, PDO::PARAM_STR);

            $stmt->execute();

            header("Location: http://localhost/employee-attendance-system/masterlist.php");

            exit();
        } catch (PDOException $e) {
            echo "Error:" . $e->getMessage();
        }

    } else {
        echo "
            <script>
                alert('Please fill in all fields!');
                window.location.href = 'http://localhost/employee-attendance-system/masterlist.php';
            </script>
        ";
    }
}
?>
