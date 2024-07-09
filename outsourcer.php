<?php
include('connection.php');
include('header.php');

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $outsourcerName = $_POST['OutsourcerName'];
    $address = $_POST['Address'];
    $contactNumber = $_POST['ContactNumber'];

    // Server-side validation for contact number
    if (preg_match('/^\d{10}$/', $contactNumber)) {
        // Contact number is valid, proceed with saving the outsourcer to the database
        $query = "INSERT INTO outsourcer (OutsourcerName, Address, ContactNumber) VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'sss', $outsourcerName, $address, $contactNumber);
        
        if (mysqli_stmt_execute($stmt)) {
            echo '<div class="alert alert-success" role="alert">Outsourcer added successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error adding outsourcer: ' . mysqli_error($conn) . '</div>';
        }

        mysqli_stmt_close($stmt);
    } else {
        echo '<div class="alert alert-danger" role="alert">Invalid contact number. It must be exactly 10 digits.</div>';
    }

    mysqli_close($conn);
}
?>

<div class="container"><h1>Outsourcer</h1></div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="outsourcerName" class="form-label">Outsourcer Name</label>
            <input type="text" class="form-control" name="OutsourcerName" id="outsourcerName" required>
        </div>

        <div class="col-md-6">
            <label for="address" class="form-label">Address</label>
            <input type="text" class="form-control" name="Address" id="address" required>
        </div>

        <div class="col-md-6">
            <label for="contactNumber" class="form-label">Contact Number</label>
            <input type="text" class="form-control" name="ContactNumber" id="contactNumber" pattern="\d{10}" title="Contact number must be exactly 10 digits" required>
        </div>

        <div class="col-sm">
            <button type="submit" class="btn btn-primary" name="SubmitButton">Save</button>
        </div>
        
        <div class="col-sm">
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
