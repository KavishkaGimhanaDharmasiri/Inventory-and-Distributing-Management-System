<?php include('connection.php');
include('header.php');
?>
<!--Lorry mangemt-->
<div class="container"><h1>Add Lorry</h1></div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

<form class="row g-3" action="#" method="post">
    <div class="col-md-6">
      <label for="inputlorrynumber" class="form-label" >Lorry Number</label>
      <input type="text" name="suppilerName" class="form-control" id="" required>
    </div>

    <div class="col-md-6">
        <label for="inputlorrydriver" class="form-label">Lorry Driver's Name</label>
        <input type="text" name="contactNumber" class="form-control" id="" required>
      </div>

    <div class="col-md-12">
      <label for="inputcapacity" class="form-label">Capacity</label>
      <input type="text" name="address" class="form-control" id="inputAddress" placeholder="10000 Kgs" required>
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


