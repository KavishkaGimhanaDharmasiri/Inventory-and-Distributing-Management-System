<?php include('connection.php');
include('header.php');



?>

<div class="container"><h1>Outsourcer</h1></div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="saveoutsourcer.php" method="post">
    <div class="col-md-6">
        <label for="" class="form-label">Outsourcer Name</label>
        <input type="text" class="form-control" name="OutsourcerName" required value="">
        </div>

        <div class="col-md-6">
        <label for="" class="form-label">Address</label>
        <input type="text" class="form-control" name="Address" required value="">
        </div>
        <div class="col-md-6">
        <label for="" class="form-label">Contact Number</label>
        <input type="text" class="form-control" name="ContactNumber" required value="">
        </div>
        <div class="col-sm" >
        <button type="submit" class="btn btn-primary" name="SubmitButton">Save</button>
        </div>
        <div class="col-sm">
        <button type="reset" class="btn btn-danger"> Clear </button>
        </div>
        <br>
    </form>

</div>

<?php include('footer.php');?>