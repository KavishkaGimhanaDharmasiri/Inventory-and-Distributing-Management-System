<?php include('header.php') ?>

<div class="container"><h1>Add Raw Materials</h1></div>
          
                            <!--center container-->
                            <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                              <form id="myForm" action="saveraw.php" method="post" class="row g-3">
                                <!--
                                  <div class="col-md-6">
                                    <label for="inputEmail4" class="form-label">Raw Materials ID</label>
                                    <input type="text" name="productId" class="form-control" id="inputcustomername">
                                  </div>
-->


                                  <div class="col-md-6">
                                    <label for="inputPassword4" class="form-label">Raws Materials Name</label>
                                    <input type="text" name="productName" class="form-control" id="inputshopname">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputMainCategory" class="form-label">Cost Price</label>
                                    <input type="text" name="MainCategory" class="form-control" id="inputMainCategory">
                                  </div>
                                  <div class="col-md-6">
                                    <label for="inputSubCategory" class="form-label">Supplier Name</label>
                                    <select>
                                      <?php
                                        include('connection.php');
                                        $suppliers = mysqli_query($conn, "select * from suppliers");
                                        while($s = mysqli_fetch_array($suppliers)) {

                                        }
        
                                      
                                      ?>
                                      <option value= "<?php echo $s['supplierId']?>">
                                      <?php echo $s['supplierName']?>
                                    </option>

                                    </select>
                                  </div>
            
                                  <div class="col-md-6">
                                    <label for="inputCity" class="form-group">Unit</label>
                                    <select name="" class="form-control" id="">
                                      <option value="">--Select Unit--</option>
                                      <option value="KG"> Kilo Gram </option>
                                      <option value="M">Meters</option>
                                      <option value="pcs">Pieces</option>

                                    </select>
                                  </div>
                                  <div class="form-group">
                                    <button type="submit" name="save_button" class="btn btn-primary">Save</button>
                                  </div>
                                  <div class="col-1">
                                        <button type="reset" class="btn btn-danger">Clear</button>
                                    </div>
          





<!-- Bootstrap JS (Popper.js and Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>



</body>
</html>