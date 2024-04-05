<?php
include ('../conn/conn.php');

if (isset($_GET['employee'])) {
    $employee = $_GET['employee'];

    try {

        $query = "DELETE FROM tbl_employee WHERE tbl_employee_id = '$employee'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            echo "
                <script>
                    alert('Employee deleted successfully!');
                    window.location.href = 'http://localhost/employee-attendance-system/masterlist.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Failed to delete employee!');
                    window.location.href = 'http://localhost/employee-attendance-system/masterlist.php';
                </script>
            ";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
