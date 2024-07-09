<?php include('header.php');
include ('connection.php');

//just copy the supplier view and edit relevant fiedls

$Supplier_List = "";

$query = "Select*from rawgrn";
$Suppliers = mysqli_query($conn,$query);

if($Suppliers){
    while($Supplier=mysqli_fetch_assoc($Suppliers)){
        $Supplier_List.="<tr>";
        $Supplier_List.="<td>{$Supplier['RawMaterialsID']}</td>";
        $Supplier_List.="<td>{$Supplier['RawMaterialstName']}</td>";
        $Supplier_List.="<td>{$Supplier['supplierId']}</td>";
        $Supplier_List.="<td>{$Supplier['supplierName']}</td>";
        $Supplier_List.="<td>{$Supplier['rawQty']}</td>";
        $Supplier_List.="</tr>";

    }

}else{
    echo "Database Query Failed.";
}
?>



<div class= "container">
<h1>View Raw Materials Stock</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Raw Materials ID</th>
            <th>Raw Materials Name</th>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Raw material Qty</th>
        </tr>

    <?php echo $Supplier_List; ?>

    </table>



</div>





<?php include('footer.php'); ?>