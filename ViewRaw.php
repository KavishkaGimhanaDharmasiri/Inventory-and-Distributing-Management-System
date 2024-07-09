<?php 
include('header.php');
include('connection.php');

$Supplier_List = "";

// Query to select all raw materials that are not deleted
$query = "SELECT * FROM rawmaterials WHERE is_deleted = FALSE";
$Suppliers = mysqli_query($conn, $query);

if($Suppliers){
    while($Supplier = mysqli_fetch_assoc($Suppliers)){
        $Supplier_List .= "<tr>";
        $Supplier_List .= "<td>{$Supplier['RawMaterialsID']}</td>";
        $Supplier_List .= "<td>{$Supplier['RawMaterialstName']}</td>";
        $Supplier_List .= "<td>{$Supplier['CostPrice']}</td>";
        $Supplier_List .= "<td>{$Supplier['SupplierId']}</td>";
        $Supplier_List .= "<td>{$Supplier['SupplierName']}</td>";
        $Supplier_List .= "<td>{$Supplier['Unit']}</td>";
        $Supplier_List .= "<td>
            <form action='delete_rawmaterial.php' method='post' style='display:inline-block;'>
                <input type='hidden' name='RawMaterialsID' value='{$Supplier['RawMaterialsID']}'>
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
    <h1>View Raw Materials</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>RawMaterial ID</th>
            <th>RawMaterial Name</th>
            <th>Cost Price</th>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Unit</th>
            <th>Action</th>
        </tr>
        <?php echo $Supplier_List; ?>
    </table>
</div>

<?php include('footer.php'); ?>
