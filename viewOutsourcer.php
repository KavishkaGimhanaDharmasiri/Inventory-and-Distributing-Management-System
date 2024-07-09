<?php 
include('header.php');
include('connection.php');

// Initialize an empty string for the supplier list
$Supplier_List = "";

// Query to select all outsourcers that are not deleted
$query = "SELECT * FROM outsourcer WHERE is_deleted = FALSE";
$Suppliers = mysqli_query($conn, $query);

// Check if the query was successful
if($Suppliers){
    // Fetch each outsourcer and create a table row with their details
    while($Supplier = mysqli_fetch_assoc($Suppliers)){
        $Supplier_List .= "<tr>";
        $Supplier_List .= "<td>{$Supplier['OutsourcerID']}</td>";
        $Supplier_List .= "<td>{$Supplier['OutsourcerName']}</td>";
        $Supplier_List .= "<td>{$Supplier['Address']}</td>";
        $Supplier_List .= "<td>{$Supplier['ContactNumber']}</td>";
        $Supplier_List .= "<td>
            <form action='delete_outsourcer.php' method='post' style='display:inline-block;'>
                <input type='hidden' name='OutsourcerID' value='{$Supplier['OutsourcerID']}'>
                <button type='submit' class='btn btn-danger btn-sm'>Delete</button>
            </form>
        </td>";
        $Supplier_List .= "</tr>";
    }
} else {
    echo "Database Query Failed: " . mysqli_error($conn);
}
?>

<div class="container">
    <h1>View Outsourcer</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Outsourcer ID</th>
            <th>Outsourcer Name</th>
            <th>Contact Address</th>
            <th>Outsourcer Number</th>
            <th>Action</th>
        </tr>
        <?php echo $Supplier_List; ?>
    </table>
</div>

<?php include('footer.php'); ?>
