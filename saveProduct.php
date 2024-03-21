<?php
    //connection
    include('connection.php');
    
  $productName=$_POST['productName'];
  $costPrice=$_POST['costPrice'];
  $sellingPrice=$_POST['sellingPrice'];

$sql=mysqli_query($con,"insert into products(productName,costPrice,sellingPrice	)
values('$productName','$costPrice','$sellingPrice')");
   if($sql){
    echo '<script type ="text/JavaScript">';  
    echo 'alert("Insert data Successfully")';  
    echo '</script>'; }
else {
echo '<script type ="text/JavaScript">';  
echo 'alert("Not Insert data Successfully")';  
echo '</script>'; }

?>
