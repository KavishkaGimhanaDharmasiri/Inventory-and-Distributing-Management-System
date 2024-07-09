


<?php

include ('header.php');
include('connection.php');

$sql = "SELECT suppilerId, suppilerName, contactNumber, address, city FROM suppliers"; // Fixed table name
$result = $conn->query($sql);

if ($result) {
  // Output data in a table
  echo "<table border='1'>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Contact Number</th>
          <th>Address</th>
          <th>City</th>
        </tr>";

  while($row = $result->fetch_assoc()) {
    echo "<tr>
            <td>" . $row["suppilerId"]. "</td>
            <td>" . $row["suppilerName"]. "</td>
            <td>" . $row["contactNumber"]. "</td>
            <td>" . $row["address"]. "</td>
            <td>" . $row["city"]. "</td>
          </tr>";
  }

  echo "</table>";
} else {
  echo "Table is not fixed";
}


$query1 = "SELECT * FROM suppilers WHERE suppilerId='$suppilerId'";
        $result1 = mysqli_query($conn, $query1);

        if (!$result1) {
            die("Database query failed: " . mysqli_error($conn));
        }
        if ($result1 && mysqli_num_rows($result1) == 1) {
        // Fetch user data
            $row1 = mysqli_fetch_assoc($result1);
            $suppilerName = $row1['suppilerName'];
            $contactNumber = $row1['contactNumber'];
            $address = $row1['address'];
            $city = $row1['city'];
        }

$conn->close();
?>
</body>
</html>
