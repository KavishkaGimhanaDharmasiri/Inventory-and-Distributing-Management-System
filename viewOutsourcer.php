<?php include('header.php');
include ('connection.php');

//just copy paste the viewsupplier code and edit the most sutable code
$Supplier_List = "";

$query = "Select*from outsourcer";
$Suppliers = mysqli_query($conn,$query);

if($Suppliers){
    while($Supplier=mysqli_fetch_assoc($Suppliers)){
        $Supplier_List.="<tr>";
        $Supplier_List.="<td>{$Supplier['OutsourcerID']}</td>";
        $Supplier_List.="<td>{$Supplier['OutsourcerName']}</td>";
        $Supplier_List.="<td>{$Supplier['Address']}</td>";
        $Supplier_List.="<td>{$Supplier['ContactNumber']}</td>";
      //  $Supplier_List.="<td>{$Supplier['city']}</td>";
        $Supplier_List.="</tr>";

    }

}else{
    echo "Database Query Failed.";
}
?>



<div class= "container">
<h1>View Outsourcer</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <table class="table table-striped table-dark">
        <tr>
            <th>Outsourcer ID</th>
            <th>Outsourcer Name</th>
            <th>Concact Number</th>
            <th>Outsourcer Address</th>
          <!--  <th>City</th> -->

        </tr>

    <?php echo $Supplier_List; ?>

    </table>



</div>





<?php include('footer.php'); ?>