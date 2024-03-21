
<?php
include('header.php');
?>


          <div class="container"><h1>Add Product</h1></div>

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


                                    <div class="col-md-6">
                                    <label for="inputSubCategory" class="form-label">Supplier Name</label>
                                    <!--getting supplier from supplier table-->
                                    <select name="supplier">
                                      <?php
                                        include('connection.php');
                                        $suppliers = mysqli_query($conn, "select * from suppliers");
                                        while($s = mysqli_fetch_array($suppliers)) {
                                      ?>
                                      <option value= "<?php echo $s['supplierId']?>">
                                      <?php echo $s['supplierName']?>
                                    </option>
                                    <?php } ?>

                                    </select>

                                      <div class="col-md-6"></br>
                                        <label for="">Product Type</label></br>
                                        <input type="radio" name="productTpye" value="Inhouse Product" required> Inhouse Product </br>
                                        <input type="radio" name="productTpye" value="Outsourcer Product" required> Outsourcer Product </br>
                                        <input type="radio" name="productTpye" value="Import Complete Product" required> Import Complete Product </br>
                                      </div>
                                     
            
                                    <div class="md-6">
                                      <button type="submit" class="btn btn-primary">Save</button>
                                    </div>

                                    <div class="md-6">
                                        <button type="reset" class="btn btn-danger">Clear</button>
                                      </div>
                                  </form>


            
                                
            
            
            
                              </div>



                              <?php include('footer.php');?>