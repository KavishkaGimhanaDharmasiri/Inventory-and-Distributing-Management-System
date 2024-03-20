<?php
    //connection
include('connection.php');
    
 

$suppilerName=$_POST['suppilerName'];
$contactNumber=$_POST['contactNumber'];
$address=$_POST['address'];
$city=$_POST['city'];

$sql=mysqli_query($conn,"insert into suppilers(suppilerName,contactNumber,address,city)
values('$suppilerName','$contactNumber','$address','$city')");
   if($sql){
    echo '<script type ="text/JavaScript">';  
    echo 'alert("Insert data Successfully")';  
    echo '</script>'; }
else {
echo '<script type ="text/JavaScript">';  
echo 'alert("Not Insert data Successfully")';  
echo '</script>'; }

include('header.php');

?>


        
                  <div class="container"><h1>Add Suppiler</h1></div>



                  <!--center container-->
                  <div class="container min-vh-100 d-flex justify-content-center align-items-center">

                    <form class="row g-3" action="suppiler.php" method="post">
                        <div class="col-md-6">
                          <label for="inputEmail4" class="form-label">Suppiler Name</label>
                          <input type="text" name="suppilerName" class="form-control" id="inputcustomername">
                        </div>

                        <div class="col-md-6">
                            <label for="inputCity" class="form-label">Contact Number</label>
                            <input type="tel" name="contactNumber" class="form-control" id="inputContact">
                          </div>

                        <div class="col-12">
                          <label for="inputAddress" class="form-label">Address</label>
                          <input type="text" name="address" class="form-control" id="inputAddress" placeholder="1234 Main St">
                        </div>
  
                        <div class="col-md-6">
                          <label for="inputCity" class="form-label">City</label>
                          <input type="text" name="city" class="form-control" id="inputCity">
                        </div>



                        <div class="col-12">
                          <button type="submit" class="btn btn-primary">Save</button>
                        </div>

                        <div class="col-1">
                                        <button type="reset" class="btn btn-danger">Clear</button>
                                    </div>

                                    <div class="col-1">
                                      <button type="edit" class="btn btn-Warning">Edit</button>
                                  </div>

                                  <div class="col-1">
                                    <button type="delect" class="btn btn-dark">Delete</button>
                                </div>
                      </form>

                    



                  </div>




                  

<?php include ('footer.php');?>