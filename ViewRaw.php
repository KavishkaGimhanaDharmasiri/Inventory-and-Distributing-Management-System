<?php include('header.php');
include ('connection.php');
?>

<?php

$Supplier_List = "";

$query = "Select*from rawmaterials";
$Suppliers = mysqli_query($conn,$query);

if($Suppliers){
    while($Supplier=mysqli_fetch_assoc($Suppliers)){
        $Supplier_List.="<tr>";
        $Supplier_List.="<td>{$Supplier['RawMaterialsID']}</td>";
        $Supplier_List.="<td>{$Supplier['RawMaterialstName']}</td>";
        $Supplier_List.="<td>{$Supplier['CostPrice']}</td>";
        $Supplier_List.="<td>{$Supplier['SupplierId']}</td>";
        $Supplier_List.="<td>{$Supplier['Unit']}</td>";
        $Supplier_List.="</tr>";

    }

}else{
    echo "Database Query Failed.";
}
?>



<div class= "container">
<h1>View RawMaterials</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>RawMaterial ID</th>
            <th>RawMaterial Name</th>
            <th>Cost Price</th>
            <th>Supplier ID</th>
            <th>Unit</th> 

        </tr>

    <?php echo $Supplier_List; ?>

    </table>



</div>





<?php include('footer.php'); ?>
