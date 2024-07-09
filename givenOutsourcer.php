<?php include('connection.php');
include('header.php');
?>
<div class="container"><h1>Given to Outsourcer</h1></div>
          
                            <!--center container-->
                            
                            <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                              <form id="myForm" action="saveraw.php" method="POST" class="row g-3">


                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Raws Materials Name</label>
                                    <input type="text" name="RawMaterialstName" class="form-control" id="RawMaterialstName" required>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputMainCategory" class="form-label">Cost Price</label>
                                    <input type="number" name="CostPrice" class="form-control" id="CostPrice"required>
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputsupplier" class="form-group">Outsourcser Name</label>
                                    <!--getting supplier from supplier table-->
                                    <select id="supplier" name="supplier" class="form-select" required>
                                      <?php
                                        include('connection.php');
                                        $outsourcerName = mysqli_query($conn, "select * from OutsourcerName");
                                        while($s = mysqli_fetch_array($outsourcerName)) {
                                      ?>
                                      <option value= "<?php echo $s['supplierId']?>">
                                      <?php echo $s['supplierName']?>
                                    </option>
                                    <?php } ?>

                                    </select>
                                  </div>
            
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-group">Unit</label>
                                    <select name="Unit" class="form-control" id="Unit" required>
                                      <option value="">--Select Unit--</option>
                                      <option value="1KG">One kilo gram </option>
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
                                        </div>
          





<?php include('footer.php');?>