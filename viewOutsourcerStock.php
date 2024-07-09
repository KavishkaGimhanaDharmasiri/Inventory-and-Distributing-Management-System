<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Retrieve outsourcer stock data
$query = "SELECT * FROM rawgrnout";
$result = mysqli_query($conn, $query);

?>

<div class="container">
    <h1>View Outsourcer Stock</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Raw Materials ID</th>
            <th>Raw Material Name</th>
            <th>Outsourcer ID</th>
            <th>Outsourcer Name</th>
            <th>Quantity</th>
        </tr>

        <?php
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                echo "<tr>";
                echo "<td>{$row['RawMaterialsID']}</td>";
                echo "<td>{$row['RawMaterialstName']}</td>";
                echo "<td>{$row['OutsourcerId']}</td>";
                echo "<td>{$row['OutsourcerName']}</td>";
                echo "<td>{$row['rawQty']}</td>";
                echo "</tr>";
            }
        } else {
            echo "<tr><td colspan='5'>No data found</td></tr>";
        }
        ?>

    </table>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
