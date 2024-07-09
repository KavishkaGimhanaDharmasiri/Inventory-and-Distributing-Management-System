<?php
include('connection.php');
include('header.php');
?>

<div class="container"><h1>Add Raw Materials</h1></div>

<!--center container-->

<div class="container min-vh-100 d-flex justify-content-center align-items-center">

  <form id="myForm" action="saveraw.php" method="POST" class="row g-3">

      <div class="col-md-6">
        <label for="RawMaterialstName" class="form-label">Raw Materials Name</label>
        <input type="text" name="RawMaterialstName" class="form-control" id="RawMaterialstName" required>
      </div>
      <div class="col-md-6">
        <label for="CostPrice" class="form-label">Cost Price</label>
        <input type="number" name="CostPrice" class="form-control" id="CostPrice" required>
      </div>
      <div class="col-md-6">
        <label for="supplier" class="form-group">Supplier Name</label>
        <!--getting supplier from supplier table-->
        <select id="supplier" name="supplier" class="form-select" required>
          <option value="">--Select Supplier--</option>
          <?php
            $suppliers = mysqli_query($conn, "SELECT * FROM suppliers WHERE is_deleted = 0");
            while($s = mysqli_fetch_array($suppliers)) {
          ?>
          <option value= "<?php echo $s['supplierId']?>">
          <?php echo $s['supplierName']?>
          </option>
          <?php } ?>
        </select>
      </div>

      <div class="col-md-6">
        <label for="Unit" class="form-group">Unit</label>
        <select name="Unit" class="form-control" id="Unit" required>
          <option value="">--Select Unit--</option>
          <option value="1KG">One kilo gram</option>
          <option value="1M">One Meter</option>
          <option value="1pcs">One Piece</option>
        </select>
      </div>

      <div class="form-group">
        <button type="submit" name="save_button" class="btn btn-primary">Save</button>
      </div>
      <div class="col-1">
        <button type="reset" class="btn btn-danger">Clear</button>
      </div>
  </form>
</div>

<?php include('footer.php');?>
