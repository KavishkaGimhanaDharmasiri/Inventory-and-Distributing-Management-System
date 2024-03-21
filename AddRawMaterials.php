<?php include('connection.php');
include('header.php');
?>
<div class="container"><h1>Add Raw Materials</h1></div>
          
                            <!--center container-->
                            <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                              <form id="myForm" action="saveraw.php" method="POST" class="row g-3">


                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Raws Materials Name</label>
                                    <input type="text" name="RawMaterialstName" class="form-control" id="RawMaterialstName">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputMainCategory" class="form-label">Cost Price</label>
                                    <input type="text" name="CostPrice" class="form-control" id="CostPrice">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputSubCategory" class="form-label">Supplier Name</label>
                                    <!--getting supplier from supplier table-->
                                    <select id="supplier" name="supplier">
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
                                  </div>
            
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-group">Unit</label>
                                    <select name="Unit" class="form-control" id="Unit">
                                      <option value="">--Select Unit--</option>
                                      <option value="1KG">One Kilo Gram </option>
                                      <option value="1M">One Meters</option>
                                      <option value="1pcs">One Pieces</option>

                                    </select>
                                  </div>

                                  <div class="form-group">
                                    <button type="submit" name="save_button" class="btn btn-primary">Save</button>
                                  </div>
                                  <div class="col-1">
                                        <button type="reset" class="btn btn-danger">Clear</button>
                                    </div>
          





<?php include('footer.php');?>