<?php
// Connection
include('connection.php');

// Check connection
if (mysqli_connect_errno()) {
    echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
}

// Query to fetch supplier IDs and names
$query = "SELECT suppilerId, suppilerName FROM suppilers";
$result = mysqli_query($con, $query);

// Check if query was successful
if (!$result) {
    echo "Error in SQL query: " . mysqli_error($con);
    exit();
}

include('header.php');

?>


<div class="container">
<form form class="row g-3" method="post" action="process_form.php">
                        <div class="col-md-6" >
                          <label for="inputsselect" class="form-label">Select Supplier:</label>
                          <select id="supplierSelect" name="supplierID">
                        </div>
                        
    
    
        <?php
        // Populate dropdown options from query result
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['suppilerId']}'>{$row['suppilerName']}</option>";
        }
        ?>
    </select>

    <br>

    <?php
    // Query to fetch product IDs and names
$query = "SELECT productId, productName FROM products";
$result = mysqli_query($con, $query);

// Check if query was successful
if (!$result) {
    echo "Error in SQL query: " . mysqli_error($con);
    exit();
}
?>

    <label for="productSelect">Select Product:</label>
    <select id="productSelect" name="productId">
        <?php
        // Populate dropdown options from query result
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<option value='{$row['productId']}'>{$row['productName']}</option>";
        }
        ?>
    </select>




    <br>
    <br>

    <div class="col-md-12" >
    <button type="submit">Submit</button>
    </div>


</form>


</div>

<?php
// Close the connection
mysqli_close($con);


//footer
include('footer.php');
?>




