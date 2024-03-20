<?php include('header.php'); ?>

          <div class="container"><h1>Register Page</h1></div>

          <!--center container-->
          <div class="container min-vh-100 d-flex justify-content-center align-items-center">

              <form id="myForm" action="#" method="post" class="row g-3">
                  <div class="col-md-6">
                    <label for="inputEmail4" class="form-label">User ID</label>
                    <input type="text" name="productId" class="form-control" id="inputcustomername">
                  </div>
              
                  <div class="col-md-6">
                  <label for="id">Name</label>
                  <input type="text" id="productId" class="form-control" name="productId" >
                  </div>

                  <div class="col-md-6">
                    <label for="inputPassword4" class="form-label">Email</label>
                    <input type="text" name="productName" class="form-control" id="inputshopname">
                  </div>

                  <div class="col-md-6">
                    <label for="inputCity" class="form-label">Concact Number</label>
                    <input type="text" name="costPrice" class="form-control" id="inputCity">
                  </div>

                  <div class="col-md-6">
                      <label for="inputCity" class="form-label">Password</label>
                      <input type="password" name="Password" class="form-control" id="inputContact">
                    </div>

                    <div class="col-md-6">
                        <label for="inputCity" class="form-label">Conform Password</label>
                        <input type="password" name="password" class="form-control" id="inputContact">
                      </div>

                  <div class="col-6">
                    <button type="submit" class="btn btn-primary">Submit</button>
                  </div>

                  <div class="col-6">
                      <button type="reset" class="btn btn-danger">Clear</button>
                    </div>
                </form>

                </div>
    
<?php include('footer.php'); ?>