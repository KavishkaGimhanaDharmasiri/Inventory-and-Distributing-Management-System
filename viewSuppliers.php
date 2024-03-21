<?php include('header.php');
include ('connection.php');

$Supplier_List = "";

$query = "Select*from suppliers";
$Suppliers = mysqli_query($conn,$query);

if($Suppliers){
    while($Supplier=mysqli_fetch_assoc($Suppliers)){
        $Supplier_List.="<tr>";
        $Supplier_List.="<td>{$Supplier['supplierId']}</td>";
        $Supplier_List.="<td>{$Supplier['supplierName']}</td>";
        $Supplier_List.="<td>{$Supplier['contactNumber']}</td>";
        $Supplier_List.="<td>{$Supplier['address']}</td>";
      //  $Supplier_List.="<td>{$Supplier['city']}</td>";
        $Supplier_List.="</tr>";

    }

}else{
    echo "Database Query Failed.";
}
?>



<div class= "container">
<h1>View Suppliers</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Supplier ID</th>
            <th>Supplier Name</th>
            <th>Concact Number</th>
            <th>Supplier Address</th>
          <!--  <th>City</th> -->

        </tr>

    <?php echo $Supplier_List; ?>

    </table>



</div>





<?php include('footer.php'); ?>