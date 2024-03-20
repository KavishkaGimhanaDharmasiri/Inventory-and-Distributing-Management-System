<?php
    //connection
$con=mysqli_connect("localhost","root","2000",'stock');
    


// Fetch the next auto-incremented ID
$result = mysqli_query($con, "SELECT AUTO_INCREMENT
                              FROM information_schema.TABLES
                              WHERE TABLE_SCHEMA = 'stock'
                              AND TABLE_NAME = 'products'");

$row = mysqli_fetch_assoc($result);
$nextID = $row['AUTO_INCREMENT'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {

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
}
?>
<?php
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


          <div class="container"><h1>Add Products-Raw Materials</h1></div>

                            <!--center container-->
                            <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                                <form action="addproduct.php" method="POST"class="row g-3">
                                    <div class="col-md-6">
                                      <label for="inputEmail4" class="form-label">Product ID</label>
                                      <input type="text" name="productId" class="form-control" id="inputcustomername">
                                    </div>



                                    <div class="col-md-6">
                                      <label for="inputPassword4" class="form-label">Product Name</label>
                                      <input type="text" name="productName" class="form-control" id="inputshopname">
                                    </div>
              
                                    <div class="col-md-6">
                                      <label for="inputCity" class="form-label">Cost Price</label>
                                      <input type="text" name="costPrice" class="form-control" id="inputCity">
                                    </div>
            
                                    <div class="col-md-6">
                                        <label for="inputCity" class="form-label">Selling Price</label>
                                        <input type="text" name="sellingPrice" class="form-control" id="inputContact">
                                      </div>

                                      <form form class="row g-3" method="post" action="addproduct.php">
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



            
                                    <div class="col-12">
                                      <button type="submit" class="btn btn-primary">Add Product</button>
                                    </div>

                                    <div class="col-12">
                                        <button type="reset" class="btn btn-danger">Clear</button>
                                      </div>
                                  </form>


            
                                
            
            
            
                              </div>



                              <?php include('footer.php');?>