<?php
include('header.php');
include('connection.php'); // Include your database connection file

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $supplierName = $_POST['supplierName'];
    $contactNumber = $_POST['contactNumber'];
    $address = $_POST['address'];

    // Server-side validation for contact number
    if (preg_match('/^\d{10}$/', $contactNumber)) {
        // Contact number is valid, proceed with saving the supplier to the database
        $query = "INSERT INTO suppliers (supplierName, contactNumber, address) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $supplierName, $contactNumber, $address);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="alert alert-success" role="alert">Supplier added successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error adding supplier: ' . mysqli_error($conn) . '</div>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<div class="alert alert-danger" role="alert">Invalid contact number. It must be exactly 10 digits.</div>';
    }

    mysqli_close($conn);
}
?>

<div class="container"><h1>Add Supplier</h1></div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="inputSupplierName" class="form-label">Supplier Name</label>
            <input type="text" name="supplierName" class="form-control" id="inputSupplierName" required>
        </div>

        <div class="col-md-6">
            <label for="inputContact" class="form-label">Contact Number</label>
            <input type="text" name="contactNumber" class="form-control" id="inputContact" pattern="\d{10}" title="Contact number must be exactly 10 digits" required>
        </div>

        <div class="col-md-12">
            <label for="inputAddress" class="form-label">Address</label>
            <input type="text" name="address" class="form-control" id="inputAddress" placeholder="1234 Main St" required>
        </div>

        <div class="col-sm">
            <button type="submit" class="btn btn-primary">Save</button>
        </div>

        <div class="col-sm">
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
