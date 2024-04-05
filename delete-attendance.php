<?php
include ('../conn/conn.php');

if (isset($_GET['attendance'])) {
    $employeeAttendance = $_GET['attendance'];

    try {

        $query = "DELETE FROM tbl_attendance WHERE tbl_attendance_id = '$employeeAttendance'";

        $stmt = $conn->prepare($query);

        $query_execute = $stmt->execute();

        if ($query_execute) {
            echo "
                <script>
                    alert('Employee attendance deleted successfully!');
                    window.location.href = 'http://localhost/employee-attendance-system/index.php';
                </script>
            ";
        } else {
            echo "
                <script>
                    alert('Failed to delete employee attendance!');
                    window.location.href = 'http://localhost/employee-attendance-system/index.php';
                </script>
            ";
        }

    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

?>
