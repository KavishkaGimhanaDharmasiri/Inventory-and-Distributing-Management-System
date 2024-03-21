<?php include ('header.php');?>
        
                  <div class="container"><h1>Add Suppiler</h1></div>

                  <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                    <form class="row g-3" action="saveSupplier.php" method="post">
                        <div class="col-md-6">
                          <label for="inputEmail4" class="form-label">Suppiler Name</label>
                          <input type="text" name="suppilerName" class="form-control" id="inputcustomername">
                        </div>

                        <div class="col-md-6">
                            <label for="inputCity" class="form-label">Contact Number</label>
                            <input type="tel" name="contactNumber" class="form-control" id="inputContact">
                          </div>

                        <div class="col-md-12">
                          <label for="inputAddress" class="form-label">Address</label>
                          <input type="text" name="address" class="form-control" id="inputAddress" placeholder="1234 Main St">
                        </div>
                        <br>

                      

                        <div class="col-sm" >
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                        <div class="col-sm">
                          <button type="reset" class="btn btn-danger"> Clear </button>
                        </div>


                      </form>

                    



                  </div>




                  

                  <?php include ('footer.php');?>