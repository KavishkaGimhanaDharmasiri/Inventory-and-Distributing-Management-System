<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Handle form submission for adding raw materials
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $rawMaterialsID = $_POST['rawMaterialsID'];
    $outsourcer = $_POST['outsourcer'];
    $qty = $_POST['qty'];

    // Check if the combination of raw materials and outsourcer already exists
    $checkQuery = "SELECT rawQty FROM rawgrnout WHERE RawMaterialsID = ? AND OutsourcerId = ?";
    $stmtCheck = mysqli_prepare($conn, $checkQuery);
    mysqli_stmt_bind_param($stmtCheck, 'ii', $rawMaterialsID, $outsourcer);
    mysqli_stmt_execute($stmtCheck);
    mysqli_stmt_bind_result($stmtCheck, $currentQty);
    mysqli_stmt_fetch($stmtCheck);
    mysqli_stmt_close($stmtCheck);

    if ($currentQty !== null) {
        // If exists, update the quantity
        $newQty = $currentQty + $qty;
        $updateQuery = "UPDATE rawgrnout SET rawQty = ? WHERE RawMaterialsID = ? AND OutsourcerId = ?";
        $stmtUpdate = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($stmtUpdate, 'iii', $newQty, $rawMaterialsID, $outsourcer);
        
        if (mysqli_stmt_execute($stmtUpdate)) {
            echo '<div class="alert alert-success" role="alert">Quantity updated successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error updating quantity: ' . mysqli_error($conn) . '</div>';
        }
        
        mysqli_stmt_close($stmtUpdate);
    } else {
        // If does not exist, insert new entry
        $insertQuery = "INSERT INTO rawgrnout (RawMaterialsID, RawMaterialstName, OutsourcerId, OutsourcerName, rawQty) 
                        SELECT rm.RawMaterialsID, rm.RawMaterialstName, o.OutsourcerID, o.OutsourcerName, ? 
                        FROM rawmaterials rm
                        INNER JOIN outsourcer o ON o.OutsourcerID = ?
                        WHERE rm.RawMaterialsID = ? AND rm.is_deleted = 0";
        
        $stmtInsert = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($stmtInsert, 'iii', $qty, $outsourcer, $rawMaterialsID);

        if (mysqli_stmt_execute($stmtInsert)) {
            echo '<div class="alert alert-success" role="alert">Data inserted successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error inserting data: ' . mysqli_error($conn) . '</div>';
        }

        mysqli_stmt_close($stmtInsert);
    }

    // Deduct the added quantity from rawgrn table
    $deductQuery = "UPDATE rawgrn SET rawQty = rawQty - ? WHERE RawMaterialsID = ? AND rawQty >= ?";
    $stmtDeduct = mysqli_prepare($conn, $deductQuery);
    mysqli_stmt_bind_param($stmtDeduct, 'iii', $qty, $rawMaterialsID, $qty);

    if (mysqli_stmt_execute($stmtDeduct)) {
        if (mysqli_stmt_affected_rows($stmtDeduct) > 0) {
            echo '<div class="alert alert-success" role="alert">Quantity deducted from rawgrn successfully.</div>';
        } else {
            echo '<div class="alert alert-warning" role="alert">Insufficient quantity in rawgrn to deduct.</div>';
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Error deducting quantity from rawgrn: ' . mysqli_error($conn) . '</div>';
    }

    mysqli_stmt_close($stmtDeduct);
    mysqli_close($conn);
}
?>

<div class="container">
    <h1>Add Raw Materials Quantity - Outsourcer</h1>
</div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="rawMaterialsID" class="form-label">Raw Materials</label>
            <select id="rawMaterialsID" name="rawMaterialsID" class="form-select" required>
                <option value="">Select Raw Material</option>
                <?php
                $rawMaterials = mysqli_query($conn, "SELECT RawMaterialsID, RawMaterialstName FROM rawmaterials WHERE is_deleted = 0");
                while ($row = mysqli_fetch_assoc($rawMaterials)) {
                    echo "<option value='" . $row['RawMaterialsID'] . "'>" . $row['RawMaterialstName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="outsourcer" class="form-label">Outsourcer</label>
            <select id="outsourcer" name="outsourcer" class="form-select" required>
                <option value="">Select Outsourcer</option>
                <?php
                $outsourcers = mysqli_query($conn, "SELECT OutsourcerID, OutsourcerName FROM outsourcer WHERE is_deleted = 0");
                while ($row = mysqli_fetch_assoc($outsourcers)) {
                    echo "<option value='" . $row['OutsourcerID'] . "'>" . $row['OutsourcerName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="qty" class="form-label">Quantity to Add</label>
            <input type="number" name="qty" class="form-control" id="qty" placeholder="Enter Quantity" required>
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Add Quantity</button>
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
