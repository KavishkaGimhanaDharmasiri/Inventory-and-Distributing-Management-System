
<?php
include('header.php');
?>


          <div class="container"><h1>Add Product</h1></div>

                            <!--center container-->
                            <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                                <form action="saveProduct.php" method="POST"class="row g-3">
                                  <!--
                                    <div class="col-md-6">
                                      <label for="inputEmail4" class="form-label">Product ID</label>
                                      <input type="text" name="productId" class="form-control" id="inputcustomername">
                                    </div>
                                    -->
                                    <div class="col-md-6">
                                      <label for="inputPassword4" class="form-label">Main Category</label>
                                      <input type="text" name="maincategory" class="form-control" id="maincategory" required>
                                    </div>




                                    <div class="col-md-6">
                                      <label for="inputPassword4" class="form-label">Product Name</label>
                                      <input type="text" name="productName" class="form-control" id="productName" required>
                                    </div>
              
                                    <div class="col-md-6">
                                      <label for="inputCity" class="form-label">Cost Price</label>
                                      <input type="number" name="costPrice" class="form-control" id="costPrice" required>
                                    </div>
                                    <div class="col-md-6">
                                      <label for="inputPassword4" class="form-label">Credit Price</label>
                                      <input type="number" name="creditprice" class="form-control" id="creditprice" required>
                                    </div>
                                    <div class="col-md-6">
                                      <label for="inputPassword4" class="form-label">Cheque Price</label>
                                      <input type="number" name="chequeprice" class="form-control" id="chequeprice" required>
                                    </div>
                                    
            
                                    <div class="col-md-6">
                                        <label for="inputCity" class="form-label">Selling Price</label>
                                        <input type="number" name="sellingPrice" class="form-control" id="sellingPrice" required>
                                      </div>


                                    <div class="col-md-6">
                                    <label for="inputSubCategory" class="form-label">Supplier Name</label>
                                    <!--getting supplier from supplier table-->
                                    <select name="supplier" class="form-select">
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
                                        <input type="radio" input class="form-check-input" name="productTpye" value="Inhouse Product" required> Inhouse Product </br>
                                        <input type="radio" input class="form-check-input" name="productTpye" value="Outsourcer Product" required> Outsourcer Product </br>
                                        <input type="radio" input class="form-check-input" name="productTpye" value="Import Complete Product" required> Import Complete Product </br>
                                      </div>
                                     
                                      <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
                                  </form>


            
                                
            
            
            
                              </div>
                                        </div>



                              <?php include('footer.php');?>