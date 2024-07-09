<?php 
include('header.php');
include('connection.php');

$Supplier_List = "";

// Query to select all suppliers that are not deleted
$query = "SELECT * FROM suppliers WHERE is_deleted = FALSE";
$Suppliers = mysqli_query($conn, $query);

if($Suppliers){
    while($Supplier = mysqli_fetch_assoc($Suppliers)){
        $Supplier_List .= "<tr>";
        $Supplier_List .= "<td>{$Supplier['supplierId']}</td>";
        $Supplier_List .= "<td>{$Supplier['supplierName']}</td>";
        $Supplier_List .= "<td>{$Supplier['contactNumber']}</td>";
        $Supplier_List .= "<td>{$Supplier['address']}</td>";
        $Supplier_List .= "<td>
            <form action='delete_supplier.php' method='post' style='display:inline-block;'>
                <input type='hidden' name='supplierId' value='{$Supplier['supplierId']}'>
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
    <h1>View Suppliers</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Contact Number</th>
            <th>Supplier Address</th>
            <th>Action</th>
        </tr>
        <?php echo $Supplier_List; ?>
    </table>
</div>

<?php include('footer.php'); ?>
