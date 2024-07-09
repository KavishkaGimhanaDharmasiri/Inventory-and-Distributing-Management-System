<?php
include('connection.php'); // Include database connection file
include('header.php'); // Include header file

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $rawmaterials = $_POST['rawmaterials'];
    $supplier = $_POST['supplier'];
    $quantity = $_POST['Quantity'];

    // Check if the raw material entry already exists in rawgrn
    $query = "SELECT * FROM rawgrn WHERE RawMaterialsID = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 'i', $rawmaterials);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    
    if (mysqli_num_rows($result) > 0) {
        // If exists, update the quantity
        $updateQuery = "UPDATE rawgrn SET rawQty = rawQty + ? WHERE RawMaterialsID = ?";
        $updateStmt = mysqli_prepare($conn, $updateQuery);
        mysqli_stmt_bind_param($updateStmt, 'ii', $quantity, $rawmaterials);
        
        if (mysqli_stmt_execute($updateStmt)) {
            echo '<div class="alert alert-success" role="alert">Quantity added successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error adding quantity: ' . mysqli_error($conn) . '</div>';
        }
        
        mysqli_stmt_close($updateStmt);
    } else {
        // If does not exist, insert new entry
        $insertQuery = "INSERT INTO rawgrn (RawMaterialsID, RawMaterialstName, rawQty, supplierId, supplierName) 
                        SELECT ?, rm.RawMaterialstName, ?, ?, s.supplierName 
                        FROM rawmaterials rm
                        INNER JOIN suppliers s ON s.supplierId = ?
                        WHERE rm.RawMaterialsID = ? AND rm.is_deleted = 0 AND s.is_deleted = 0";
        $insertStmt = mysqli_prepare($conn, $insertQuery);
        mysqli_stmt_bind_param($insertStmt, 'iiiii', $rawmaterials, $quantity, $supplier, $supplier, $rawmaterials);
        
        if (mysqli_stmt_execute($insertStmt)) {
            echo '<div class="alert alert-success" role="alert">Raw material added successfully.</div>';
        } else {
            echo '<div class="alert alert-danger" role="alert">Error adding raw material: ' . mysqli_error($conn) . '</div>';
        }
        
        mysqli_stmt_close($insertStmt);
    }

    mysqli_stmt_close($stmt);
    mysqli_close($conn);
}
?>

<div class="container"><h1>Raw Materials Good Received Note</h1></div> 

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="rawmaterials" class="form-label">Raw Materials</label>
            <select id="rawmaterials" name="rawmaterials" class="form-select" required>
                <option value="">Select Raw Material</option>
                <?php
                $rawmaterials_query = mysqli_query($conn, "SELECT * FROM rawmaterials WHERE is_deleted = 0");
                while ($row = mysqli_fetch_array($rawmaterials_query)) {
                    echo "<option value='{$row['RawMaterialsID']}'>{$row['RawMaterialstName']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="supplier" class="form-label">Supplier</label>
            <select id="supplier" name="supplier" class="form-select" required>
                <option value="">Select Supplier</option>
                <?php
                $suppliers_query = mysqli_query($conn, "SELECT * FROM suppliers WHERE is_deleted = 0");
                while ($row = mysqli_fetch_array($suppliers_query)) {
                    echo "<option value='{$row['supplierId']}'>{$row['supplierName']}</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="Quantity" class="form-label">Quantity</label>
            <input type="text" name="Quantity" class="form-control" id="Quantity" placeholder="Enter Quantity" required>
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); ?>
