<?php
include('header.php'); // Include header file if it exists
include('connection.php'); // Include database connection file

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $rawMaterialsID = $_POST['rawMaterialsID'];
    $outsourcerID = $_POST['outsourcerID'];
    $qty = $_POST['qty'];

    // Validate quantity
    if ($qty <= 0) {
        echo '<div class="alert alert-danger" role="alert">Quantity must be greater than zero.</div>';
    } else {
        // Check current quantity in rawgrn table
        $query = "SELECT rawQty FROM rawgrn WHERE RawMaterialsID = ?";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, 'i', $rawMaterialsID);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $currentQty);
        mysqli_stmt_fetch($stmt);
        mysqli_stmt_close($stmt);

        // Calculate new quantity after deduction
        $newQty = $currentQty - $qty;

        if ($newQty < 0) {
            echo '<div class="alert alert-danger" role="alert">Cannot deduct more than available quantity.</div>';
        } else {
            // Update rawgrn table
            $updateQuery = "UPDATE rawgrn SET rawQty = ? WHERE RawMaterialsID = ?";
            $updateStmt = mysqli_prepare($conn, $updateQuery);
            mysqli_stmt_bind_param($updateStmt, 'ii', $newQty, $rawMaterialsID);
            
            if (mysqli_stmt_execute($updateStmt)) {
                // Insert into rawgrnout table
                $insertQuery = "INSERT INTO rawgrnout (RawMaterialsID, RawMaterialstName, OutsourcerId, OutsourcerName, rawQty) 
                                SELECT ?, rm.RawMaterialstName, ?, os.OutsourcerName, ? 
                                FROM rawmaterials rm
                                INNER JOIN outsourcers os ON os.OutsourcerId = ?
                                WHERE rm.RawMaterialsID = ?";
                
                $insertStmt = mysqli_prepare($conn, $insertQuery);
                mysqli_stmt_bind_param($insertStmt, 'iiiii', $rawMaterialsID, $outsourcerID, $qty, $outsourcerID, $rawMaterialsID);
                
                if (mysqli_stmt_execute($insertStmt)) {
                    echo '<div class="alert alert-success" role="alert">Raw materials outgoing record added successfully.</div>';
                } else {
                    echo '<div class="alert alert-danger" role="alert">Error adding raw materials outgoing record: ' . mysqli_error($conn) . '</div>';
                }
                
                mysqli_stmt_close($insertStmt);
            } else {
                echo '<div class="alert alert-danger" role="alert">Error deducting quantity: ' . mysqli_error($conn) . '</div>';
            }

            mysqli_stmt_close($updateStmt);
        }
    }

    mysqli_close($conn);
}
?>

<div class="container"><h1>Add Raw Materials Outgoing Record</h1></div>

<div class="container min-vh-100 d-flex justify-content-center align-items-center">
    <form class="row g-3" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <div class="col-md-6">
            <label for="rawMaterialsID" class="form-label">Raw Materials ID</label>
            <!-- Assuming you have a table or source for Raw Materials -->
            <select id="rawMaterialsID" name="rawMaterialsID" class="form-select" required>
                <option value="">Select Raw Material</option>
                <?php
                $rawMaterials = mysqli_query($conn, "SELECT RawMaterialsID, RawMaterialstName FROM rawmaterials");
                while ($row = mysqli_fetch_assoc($rawMaterials)) {
                    echo "<option value='" . $row['RawMaterialsID'] . "'>" . $row['RawMaterialstName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="outsourcerID" class="form-label">Outsourcer ID</label>
            <!-- Assuming you have a table or source for Outsourcers -->
            <select id="outsourcerID" name="outsourcerID" class="form-select" required>
                <option value="">Select Outsourcer</option>
                <?php
                $outsourcers = mysqli_query($conn, "SELECT OutsourcerId, OutsourcerName FROM outsourcers");
                while ($row = mysqli_fetch_assoc($outsourcers)) {
                    echo "<option value='" . $row['OutsourcerId'] . "'>" . $row['OutsourcerName'] . "</option>";
                }
                ?>
            </select>
        </div>

        <div class="col-md-6">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" name="qty" class="form-control" id="qty" placeholder="Enter Quantity" required>
        </div>

        <div class="col-md-6">
            <button type="submit" class="btn btn-primary">Save</button>
            <button type="reset" class="btn btn-danger">Clear</button>
        </div>
    </form>
</div>

<?php include('footer.php'); // Include footer file if it exists ?>
