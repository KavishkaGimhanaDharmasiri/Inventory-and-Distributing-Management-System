<?php include('connection.php');
include('header.php');
?>

<div class="container"><h1>Raw Materials Good Received Note</h1></div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

<form class="row g-3" action="saverawgrn.php" method="post">
    <div class="col-md-6">
      <label for="inputEmail4" class="form-label">Raw Materials ID</label>
      <!--getting rawmateril id from raw table
        just copy the supplier fec array replace with most wanted names
    -->
      <select id="rawmaterials" name="rawmaterials">
      <?php
      include('connection.php');
      $suppliers = mysqli_query($conn, "select * from rawmaterials");
       while($s = mysqli_fetch_array($suppliers)) {
        ?>
      <option value= "<?php echo $s['RawMaterialsID']?>">
      <?php echo $s['RawMaterialstName']?>
      </option>
      <?php } ?>

    </select>
    </div>

    <div class="col-md-6">
        <label for="inputCity" class="form-label">Supplier ID</label>
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
      <label for="inputAddress" class="form-label">Quantity</label>
      <input type="text" name="Quantity" class="form-control" id="Quantity" placeholder="Enter the Quantity">
    </div>
    <br>
   

  

    <div class="col-sm-md-6" >
      <button type="submit" class="btn btn-primary">Save</button>
    </div>

    <div class="col-sm">
      <button type="reset" class="btn btn-danger"> Clear </button>
    </div>
    </form>
    </div>
    <br>



<?php include('footer.php'); ?>