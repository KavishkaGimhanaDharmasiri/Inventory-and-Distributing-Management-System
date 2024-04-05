<?php
include("../conn/conn.php");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['employee_name'], $_POST['employee_department'])) {
        $employeeName = $_POST['employee_name'];
        $employeeDepartment = $_POST['employee_department'];
        $generatedCode = $_POST['generated_code'];

        try {
            $stmt = $conn->prepare("INSERT INTO tbl_employee (employee_name, employee_department, generated_code) VALUES (:employee_name, :employee_department, :generated_code)");
            
            $stmt->bindParam(":employee_name", $employeeName, PDO::PARAM_STR); 
            $stmt->bindParam(":employee_department", $employeeDepartment, PDO::PARAM_STR);
            $stmt->bindParam(":generated_code", $generatedCode, PDO::PARAM_STR);

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
